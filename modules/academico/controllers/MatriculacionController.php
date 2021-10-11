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
// use app\modules\academico\models\EnrolamientoAgreement;
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

        '1' => ["min" => 2, "max" => 6], //Asocciate
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

    /***********************************************  funciones nuevas matriculacion  *****************************************/

    private function getCreditoPay(){
        $arrCredito = $this->arrCredito;
        for($i = 1; $i <= count($arrCredito); $i++){
            $arrCredito[$i] = Academico::t('matriculacion', $arrCredito[$i]);
        }
        return $arrCredito;
    }

    private function getEstadoAprobar(){
        $estadoAprobar = $this->estadoAprobar;
        for($i = 1; $i <= count($estadoAprobar); $i++){
            $arrCredito[$i] = Academico::t('matriculacion', $estadoAprobar[$i]);
        }
        return $estadoAprobar;
    }

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
        $today               = date("Y-m-d H:i:s");
        $result_process      = $matriculacion_model->checkToday($today, $per_id);

        $usu_id      = Yii::$app->session->get("PB_iduser");
        $mod_usuario = Usuario::findIdentity($usu_id);

        if ($mod_usuario->usu_upreg == 0) {
            if (Yii::$app->session->get("PB_perid") < 1000) {
                return $this->redirect(['perfil/index', 'per_id' => base64_encode($per_id)]);
            }
            return $this->redirect(['perfil/index']);
        }

        if (count($result_process) > 0) {
            /*   * Exist a register process */
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
                        $data_student      = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
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
                        $dataCat   = ArrayHelper::map($mod_est->getCategoryCost(), "Cod", "Precio");
                        $modCode   = $modModalidad->getCodeCCostoxModalidad($modelPla->mod_id);
                        $dataMat   = ArrayHelper::map($mod_est->getGastosMatriculaOtros($modCode['Cod']), "Cod", "Precio");
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
        //\app\models\Utilities::putMessageLogFile(print_r($mod_persona,true));

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

                \app\models\Utilities::putMessageLogFile("est_id: ".$est_id);
                \app\models\Utilities::putMessageLogFile("pfes_referencia: ".$pfes_referencia);
                \app\models\Utilities::putMessageLogFile("pfes_banco: ".$pfes_banco);
                \app\models\Utilities::putMessageLogFile("fpag_id: ".$fpag_id);
                \app\models\Utilities::putMessageLogFile("pfes_valor_pago: ".$pfes_valor_pago);
                \app\models\Utilities::putMessageLogFile("pfes_fecha_pago: ".$pfes_fecha_pago);
                \app\models\Utilities::putMessageLogFile("pfes_observacion: ".$pfes_observacion);
                \app\models\Utilities::putMessageLogFile("imagen: ".$imagen);
                \app\models\Utilities::putMessageLogFile("usu_id: ".$usu_id);



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

                //print_r($resp_pagofactura);die();

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

    public function actionRegistry($id) { // pantalla para aprobar matriculacion de estudiante
        $model = RegistroOnline::findOne($id);
        if ($model) {
            $data = Yii::$app->request->get();
            $rama_id = $data['rama_id'];
            $modelRegAd = RegistroAdicionalMaterias::findOne(['rama_id' => $rama_id, 'rama_estado' => '1', 'rama_estado_logico' => '1',]);
            $matriculacion_model = new Matriculacion();
            $model_registroPago = new RegistroPagoMatricula();
            $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($model->per_id, $model->pes_id);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($id,0);
            
            // get subjects by Rama_id
            $inList = "";
            $inList .= (isset($modelRegAd->roi_id_1))?($modelRegAd->roi_id_1):"";
            $inList .= (isset($modelRegAd->roi_id_2))?("," . $modelRegAd->roi_id_2):"";
            $inList .= (isset($modelRegAd->roi_id_3))?("," . $modelRegAd->roi_id_3):"";
            $inList .= (isset($modelRegAd->roi_id_4))?("," . $modelRegAd->roi_id_4):"";
            $inList .= (isset($modelRegAd->roi_id_5))?("," . $modelRegAd->roi_id_5):"";
            $inList .= (isset($modelRegAd->roi_id_6))?("," . $modelRegAd->roi_id_6):"";
            $materiasxEstudiante = RegistroOnlineItem::find()->where("ron_id = $id and roi_estado = 1 and roi_estado_logico = 1 and roi_id in ($inList)")->asArray()->all();

            $dataModel = $model_registroPago->getRegistroPagoMatriculaByRegistroOnline($id, $model->per_id);
            $modelFP = new FormaPago();
            $model_rpm = RegistroPagoMatricula::findOne($dataModel["Id"]);
            $arr_forma_pago = $modelFP->consultarFormaPago();
            unset($arr_forma_pago[0]);
            unset($arr_forma_pago[1]);
            unset($arr_forma_pago[2]);
            unset($arr_forma_pago[6]);
            unset($arr_forma_pago[7]);
            unset($arr_forma_pago[8]);
            $arr_forma_pago = ArrayHelper::map($arr_forma_pago, "id", "value");

            $dataProvider = new ArrayDataProvider([
                'key' => 'roi_id',
                'allModels' => $materiasxEstudiante,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
            ]);

            $emp_id = Yii::$app->session->get("PB_idempresa");
            $arrCarrera = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id);
            $eaca_id = $arrCarrera['eaca_id'];
            $mod_id = $arrCarrera['mod_id'];
            $uaca_id = $arrCarrera['uaca_id'];
            //$model_roc = RegistroOnlineCuota::findAll(['ron_id' => $id, 'roc_estado' => '1', 'roc_estado_logico' => '1']);
            $model_roc = RegistroOnlineCuota::findAll(['rpm_id' => $modelRegAd->rpm_id, 'ron_id' => $id, 'roc_estado' => '1', 'roc_estado_logico' => '1']);
            $dataValue = array();
            $con = 0;
            $model_can = CancelacionRegistroOnline::findOne(['ron_id' => $id, 'cron_estado' => '1', 'cron_estado_logico' => '1']);
            if($model_can){/*
                $modelEnroll = EnrolamientoAgreement::findOne(['ron_id' => $id, 'rpm_id' => $modelRegAd->rpm_id, 'per_id' => $model->per_id, 'eaca_id' => $eaca_id, 'uaca_id' => $uaca_id, 'eagr_estado' => '1', 'eagr_estado_logico' => '1',]);
                $value_total = number_format($modelEnroll->eagr_costo_programa_est, 2, '.', ',');
                foreach($model_roc as $key => $value){
                    $con++;
                    $cost = $value->roc_costo;
                    $ven  = $value->roc_vencimiento;
                    if($con == 1){
                        $cost = $modelEnroll->eagr_primera_cuota_est;
                        $ven = $modelEnroll->eagr_primera_ven_est;
                    }
                    if($con == 2){
                        $cost = $modelEnroll->eagr_segunda_cuota_est;
                        $ven = $modelEnroll->eagr_segunda_ven_est;
                    }
                    if($con == 3){
                        $cost = $modelEnroll->eagr_tercera_cuota_est;
                        $ven = $modelEnroll->eagr_tercera_ven_est;
                    }
                    if($con == 4){
                        $cost = $modelEnroll->eagr_cuarta_cuota_est;
                        $ven = $modelEnroll->eagr_cuarta_ven_est;
                    }
                    if($con == 5){
                        $cost = $modelEnroll->eagr_quinta_cuota_est;
                        $ven = $modelEnroll->eagr_quinta_ven_est;
                    }
                    $dataValue[] = ['Id' => $con, 'Pago' => Academico::t('matriculacion',"Payment") . ' #' . $con, 'Vencimiento' => $ven, 'Porcentaje' => $value->roc_porcentaje, 'Valor' => '$'.(number_format($cost, 2, '.', ','))];
                }
            }else{*/
                foreach($model_roc as $key => $value){
                    $con++;
                    $dataValue[] = ['Id' => $con, 'Pago' => Academico::t('matriculacion',"Payment") . ' #' . $con, 'Vencimiento' => $value->roc_vencimiento, 'Porcentaje' => $value->roc_porcentaje, 'Valor' => '$'.(number_format($value->roc_costo, 2, '.', ','))];
                }
            }
            
            $dataProvider2 = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $dataValue,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ["Pago"],
                ],
            ]);

            return $this->render('registry', [
                        "materiasxEstudiante" => $dataProvider,
                        "materias" => $dataPlanificacion,
                        "data_student" => $data_student,
                        "ron_id" => $id,
                        "rpm_id" => $dataModel["Id"],
                        "per_id" => $model_rpm->per_id,
                        "matriculacion_model" => RegistroPagoMatricula::findOne($dataModel["Id"]),
                        'arr_forma_pago' => $arr_forma_pago,
                        'arr_credito' => $this->getCreditoPay(),
                        'value_credit' => $model_rpm->rpm_tipo_pago,
                        'value_payment' => $model_rpm->fpag_id,
                        'value_total' => number_format($model_rpm->rpm_total, 2, '.', ','),
                        'value_interes' => number_format($model_rpm->rpm_interes, 2, '.', ','),
                        'value_financiamiento' => number_format($model_rpm->rpm_financiamiento, 2, '.', ','),
                        'dataGrid' => $dataProvider2,
                        'estadoAprobar' => $this->getEstadoAprobar(),

            ]);
        }
        return $this->redirect('index');
    }

    public function actionView($id) { // pantalla para ver la aprobacion de la matriculacion de un estudiante
        $model = RegistroOnline::findOne($id);
        if ($model) {
            $data = Yii::$app->request->get();
            $rama_id = $data['rama_id'];
            $modelRegAd = RegistroAdicionalMaterias::findOne(['rama_id' => $rama_id, 'rama_estado' => '1', 'rama_estado_logico' => '1',]);
            $matriculacion_model = new Matriculacion();
            $model_registroPago = new RegistroPagoMatricula();
            $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($model->per_id, $model->pes_id);
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($id,0);
            // get subjects by Rama_id
            $inList = "";
            $inList .= (isset($modelRegAd->roi_id_1))?($modelRegAd->roi_id_1):"";
            $inList .= (isset($modelRegAd->roi_id_2))?("," . $modelRegAd->roi_id_2):"";
            $inList .= (isset($modelRegAd->roi_id_3))?("," . $modelRegAd->roi_id_3):"";
            $inList .= (isset($modelRegAd->roi_id_4))?("," . $modelRegAd->roi_id_4):"";
            $inList .= (isset($modelRegAd->roi_id_5))?("," . $modelRegAd->roi_id_5):"";
            $inList .= (isset($modelRegAd->roi_id_6))?("," . $modelRegAd->roi_id_6):"";
            $materiasxEstudiante = RegistroOnlineItem::find()->where("ron_id = $id and roi_estado = 1 and roi_estado_logico = 1 and roi_id in ($inList)")->asArray()->all();

            $dataModel = $model_registroPago->getRegistroPagoMatriculaByRegistroOnline($id, $model->per_id);
            $modelFP = new FormaPago();
            $model_rpm = RegistroPagoMatricula::findOne($dataModel["Id"]);
            $arr_forma_pago = $modelFP->consultarFormaPago();
            unset($arr_forma_pago[0]);
            unset($arr_forma_pago[1]);
            unset($arr_forma_pago[2]);
            unset($arr_forma_pago[6]);
            unset($arr_forma_pago[7]);
            unset($arr_forma_pago[8]);
            $arr_forma_pago = ArrayHelper::map($arr_forma_pago, "id", "value");
            $arr_estado = $this->getEstadoAprobar();
            $arr_estado[0] = Academico::t('matriculacion',"To Validate");

            $dataProvider = new ArrayDataProvider([
                'key' => 'roi_id',
                'allModels' => $materiasxEstudiante,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
            ]);

            $emp_id = Yii::$app->session->get("PB_idempresa");
            $arrCarrera = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id);
            $eaca_id = $arrCarrera['eaca_id'];
            $mod_id = $arrCarrera['mod_id'];
            $uaca_id = $arrCarrera['uaca_id'];
            //$model_roc = RegistroOnlineCuota::findAll(['ron_id' => $id, 'roc_estado' => '1', 'roc_estado_logico' => '1']);
            $model_roc = RegistroOnlineCuota::findAll(['rpm_id' => $modelRegAd->rpm_id, 'ron_id' => $id, 'roc_estado' => '1', 'roc_estado_logico' => '1']);
            $dataValue = array();

            $con = 0;
            $model_can = CancelacionRegistroOnline::findOne(['ron_id' => $id, 'cron_estado' => '1', 'cron_estado_logico' => '1']);
            if($model_can){
               /* $modelEnroll = EnrolamientoAgreement::findOne(['ron_id' => $id, 'rpm_id' => $modelRegAd->rpm_id, 'per_id' => $model->per_id, 'eaca_id' => $eaca_id, 'uaca_id' => $uaca_id, 'eagr_estado' => '1', 'eagr_estado_logico' => '1',]);
                $value_total = number_format($modelEnroll->eagr_costo_programa_est, 2, '.', ',');
                foreach($model_roc as $key => $value){
                    $con++;
                    $cost = $value->roc_costo;
                    $ven  = $value->roc_vencimiento;
                    if($con == 1){
                        $cost = $modelEnroll->eagr_primera_cuota_est;
                        $ven = $modelEnroll->eagr_primera_ven_est;
                    }
                    if($con == 2){
                        $cost = $modelEnroll->eagr_segunda_cuota_est;
                        $ven = $modelEnroll->eagr_segunda_ven_est;
                    }
                    if($con == 3){
                        $cost = $modelEnroll->eagr_tercera_cuota_est;
                        $ven = $modelEnroll->eagr_tercera_ven_est;
                    }
                    if($con == 4){
                        $cost = $modelEnroll->eagr_cuarta_cuota_est;
                        $ven = $modelEnroll->eagr_cuarta_ven_est;
                    }
                    if($con == 5){
                        $cost = $modelEnroll->eagr_quinta_cuota_est;
                        $ven = $modelEnroll->eagr_quinta_ven_est;
                    }
                    $dataValue[] = ['Id' => $con, 'Pago' => Academico::t('matriculacion',"Payment") . ' #' . $con, 'Vencimiento' => $ven, 'Porcentaje' => $value->roc_porcentaje, 'Valor' => '$'.(number_format($cost, 2, '.', ','))];
                }
            }else{*/
                foreach($model_roc as $key => $value){
                    $con++;
                    $dataValue[] = ['Id' => $con, 'Pago' => Academico::t('matriculacion',"Payment") . ' #' . $con, 'Vencimiento' => $value->roc_vencimiento, 'Porcentaje' => $value->roc_porcentaje, 'Valor' => '$'.(number_format($value->roc_costo, 2, '.', ','))];
                }
            }
            
            $dataProvider2 = new ArrayDataProvider([
                'key' => 'Id',
                'allModels' => $dataValue,
                'pagination' => [
                    'pageSize' => Yii::$app->params["pageSize"],
                ],
                'sort' => [
                    'attributes' => ["Pago"],
                ],
            ]);

            return $this->render('view', [
                        "materiasxEstudiante" => $dataProvider,
                        "materias" => $dataPlanificacion,
                        "data_student" => $data_student,
                        "ron_id" => $id,
                        "rpm_id" => $dataModel["Id"],
                        "per_id" => $model_rpm->per_id,
                        "matriculacion_model" => RegistroPagoMatricula::findOne($dataModel["Id"]),
                        'arr_forma_pago' => $arr_forma_pago,
                        'arr_credito' => $this->getCreditoPay(),
                        'value_credit' => $model_rpm->rpm_tipo_pago,
                        'value_payment' => $model_rpm->fpag_id,
                        'value_total' => number_format($model_rpm->rpm_total, 2, '.', ','),
                        'value_interes' => number_format($model_rpm->rpm_interes, 2, '.', ','),
                        'value_financiamiento' => number_format($model_rpm->rpm_financiamiento, 2, '.', ','),
                        'dataGrid' => $dataProvider2,
                        'estadoAprobar' => $arr_estado,
                        'esAprobado' => $model_rpm->rpm_estado_aprobacion,
                        'observacion' => $model_rpm->rpm_observacion,
                        'fileRegistration' => $model_rpm->rpm_hoja_matriculacion,
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
     * @modify  Luis Cajamarca <analista.desarrollo@uteg.edu.ec>
     * @param
     * @return
     */
    public function actionRegistro($uper_id=Null) { // pantalla para que el estudiante seleccione las materias a registrarse
        $_SESSION['JSLANG']['You must choose at least one Subject to Cancel Registration'] = Academico::t('matriculacion', 'You must choose at least one Subject to Cancel Registration');
        $_SESSION['JSLANG']['You must choose at least a number or subjects '] = Academico::t('matriculacion', 'You must choose at least a number or subjects ');
        $_SESSION['JSLANG']['You must choose at least two'] = Academico::t('matriculacion', 'You must choose at least two');
        $_SESSION['JSLANG']['You must choose at least subject'] = Academico::t('matriculacion', 'You must choose at least subjects');
        $_SESSION['JSLANG']['The number of subject that you can cancel is '] = Academico::t('matriculacion', 'The number of subject that you can cancel is ');
        $_SESSION['JSLANG']['You must choose_the maximum of six'] = Academico::t('matriculacion', 'You must choose_the maximum of six');

        $per_id = $uper_id ? $uper_id:Yii::$app->session->get("PB_perid");
        $usuario = @Yii::$app->user->identity->usu_id;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();

            
            if (Yii::$app->session->get("PB_perid") < 1000) {
                $per_id = $data['per_id'];
            }
            

            try{
                if (isset($data["pes_id"])) {
                    $modelPersona = Persona::findOne($per_id);
                                        \app\models\Utilities::putMessageLogFile(1);
                    

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
                    \app\models\Utilities::putMessageLogFile("matricula: " . $matricula);
                    $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
                    $pes_id = $data["pes_id"];
                    $modalidad = $data["modalidad"];
                    $carrera = $data["carrera"];
                    $materias = $data["materias"];
                    $matStudent=$matricula['valor'];
                    $semestre = 0;
                    $dataMaterias = $matriculacion_model->getInfoMallaEstudiante($per_id);
                    $modCode = $modModalidad->getCodeCCostoxModalidad($modelPla->mod_id);
                    
                    // Generacion de #Orden
                    $con1 = \Yii::$app->db_facturacion;
                    Secuencias::initSecuencia($con1, 1, 1, 1, "RON", "PAGO REGISTRO ONLINE");
                    $numOrden = str_pad(Secuencias::nuevaSecuencia($con1, 1, 1, 1, 'RON'), 8, "0", STR_PAD_LEFT);
                    
                    $RegistroOnline = RegistroOnline::find()->select("ron_id")->where(["per_id" => $per_id, "pes_id" => $pes_id ])->asArray()->all();
                    
                    if(empty($RegistroOnline)){
                        $registro_online_model = new RegistroOnline();

                        $ron_id = 0;
                    }else
                        $ron_id = $RegistroOnline[0]['ron_id']; 
                    
                        
                        
                    if($ron_id == 0){
                        $id = $registro_online_model->insertRegistroOnline(
                            $per_id, $pes_id, strval($numOrden), strval($modalidad), strval($carrera), strval($semestre), strval($mod_est->est_categoria), 0, 0, 0, $matricula['valor'], 1/*CAMBIAR ESTE VALOR OJO!!!!!!!!!!!*/, $usuario
                        );
                    }else{
                        $id = $ron_id;
                    }
                    

                    if ($id > 0) {
                        $ron_id = $id;//;$registro_online_model->getPrimaryKey();
                        $RegistroOnline=RegistroOnline::find()->select("ron_valor_gastos_adm")->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one();
                        $costoMaterias = 0;
                        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
                        $dataPlanificacion = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);
                        $num_min = 2;
                        $num_max = 6;
                        if (count($dataPlanificacion) <= 2) {
                            $num_min = count($dataPlanificacion);
                            $num_max = count($dataPlanificacion);
                        } else {
                            $num_min = 2;
                        }
                        // Se debe buscar la materia por medio del Alias y obtener el codigo de la asignatura, creditos, y codigo de la malla
                        $contMateria = 0;
                        $bloques = $data["bloque"];
                        $horas   = $data["hora"];
                        $paralelos = $data["paralelo"];

                        $rois_insertados = [];

                        foreach ($materias as $materia) {
                            $costo      = 0;
                            $creditos   = 0;
                            $codMateria = 0;
                            $asignatura = $materia;
                            $bloque     = $bloques[$contMateria];
                            $hora       = $horas[$contMateria];
                            $paralelo   = $paralelos[$contMateria];
                            foreach($dataMaterias as $key => $value){
                                if(trim(strtolower($value['Asignatura'])) == trim(strtolower($materia))){
                                    $asignatura = $value['Asignatura'];
                                    $creditos   = $value['AsigCreditos'];
                                    $codMateria = $value['MallaCodAsig'];
                                    $costo      = $creditos * $value['CostoCredito'];
                                    $totalPago += $costo;
                                }
                            }//foreach

                            $registro_online_item_model = new RegistroOnlineItem();

                            $id_roi = $registro_online_item_model->insertRegistroOnlineItem(
                                $id, strval($codMateria), strval($asignatura), strval($creditos), $costo, strval($bloque), strval($hora), strval($paralelo), $usuario
                            );

                            $rois_insertados[] = $id_roi;

                            $contMateria++;


                        }//foreach

                        //Inicio de validacion de pendiente pagos
                        if($RegistroOnline['ron_valor_gastos_adm']<=0){
                            $roi_bloque = RegistroOnlineItem::find()->select("roi_bloque")->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                            // Tomar el valor actual de gastos administrativos
                            $block_roi= RegistroOnlineItem::find()->where(['ron_id' => $id])->asArray()->one()['roi_bloque'];
                            $gastos_administrativos_valor = GastoAdministrativo::find()->where(['mod_id' => $modalidad])->asArray()->one()['gadm_gastos_varios'];
                            $bloques = $bloque[0]; // Tomar el primer bloque
                            $mitad = 1; // Empezar asumiendo que se toma 1 solo bloque
                            
                            if($block_roi==$bloque){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                                 // Así que se divide a la mitad
                                $gastos_pendientes=$gastos_administrativos_valor;
                                $gastos_administrativos_valor = $gastos_administrativos_valor * 1;
                                $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                                //break; // Salir del foreach
                            }else{
                                $gastos_administrativos_valor = $gastos_administrativos_valor * 2;
                                $gastos_pendientes=$gastos_administrativos_valor;
                                $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                            }
                        
                        }else{
                            $RegistroOnlineP=RegistroOnline::find()->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one()['ron_valor_gastos_pendientes'];
                            $RegistroOnlineGastos=RegistroOnline::find()->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one()['ron_valor_gastos_adm'];
                            if($RegistroOnlineP==0 and $RegistroOnlineGastos>0){
                                $roi_bloque = RegistroOnlineItem::find()->select("roi_bloque")->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                                $gastos_administrativos_valor = GastoAdministrativo::find()->where(['mod_id' => $modalidad])->asArray()->one()['gadm_gastos_varios'];
                                $gastos_registro = RegistroOnline::find()->where(['ron_id' => $id])->asArray()->one()['ron_valor_gastos_adm'];
                                if($gastos_administrativos_valor==$RegistroOnlineGastos){
                                    $gastos_pendientes=0;
                                    $gastos_administrativos_valor = $gastos_administrativos_valor * 2;
                                    $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);

                                }else{
                                    $gastos_administrativos_valor = $gastos_administrativos_valor * 2; // 300
                                    $gastos_pendientes=0; // 0
                                    $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                                }
                            }
                        }
                        //fin de validacion de pendiente pagos
                        $RegistroAdiconal=RegistroAdicionalMaterias::find()->select("rama_id")->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->all();
                        if (empty($RegistroAdiconal)){

                                $roi_id = RegistroOnlineItem::find()->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                                $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                                $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $modelPla['pla_id']);
                                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                                //\app\models\Utilities::putMessageLogFile("ron: " . $id);
                                //\app\models\Utilities::putMessageLogFile("pla: " . $pla_id);
                                $result_periodo=$matriculacion_model->checkPeriodo($pla_id);
                                $paca_id=$result_periodo[0]['paca_id'];
                                //\app\models\Utilities::putMessageLogFile("paca: " . $paca_id);
                                $registro_adicional_materias_model  = new RegistroAdicionalMaterias();
                                $RegistroAdd=RegistroAdicionalMaterias::find()->select("rpm_id")->where(["per_id" => $per_id, "ron_id" => $id ])->asArray()->all();

                                if(empty($RegistroAdd['rpm_id'])){
                                    $id_rama = $registro_adicional_materias_model->insertRegistroAdicionalMaterias(
                                        $id, $per_id, $pla_id, $paca_id,
                                        $roi_id['0']['roi_id'],
                                        $roi_id['1']['roi_id'],
                                        $roi_id['2']['roi_id'],
                                        $roi_id['3']['roi_id'],
                                        $roi_id['4']['roi_id'],
                                        $roi_id['5']['roi_id'],
                                        $roi_id['6']['roi_id'],
                                        $roi_id['7']['roi_id'],
                                        $usuario

                                    );
                                }

                            } else{
                                $RegistroAdicionalMaterias = RegistroAdicionalMaterias::find()->where(['ron_id' => $id, 'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();

                                if(isset($RegistroAdicionalMaterias)){
                                    $i = 0;
                                    for ($x=0; $x < 8; $x++) { 
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
                                    $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $modelPla['pla_id']);
                                    $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                                    $result_periodo=$matriculacion_model->checkPeriodo($pla_id);
                                    $paca_id=$result_periodo[0]['paca_id'];
                                    
                                    $id_rama = (new RegistroAdicionalMaterias())->insertRegistroAdicionalMaterias(
                                        $id, $per_id, $pla_id, $paca_id,
                                        isset($rois_insertados[0]) ? $rois_insertados[0] : NULL,
                                        isset($rois_insertados[1]) ? $rois_insertados[1] : NULL,
                                        isset($rois_insertados[2]) ? $rois_insertados[2] : NULL,
                                        isset($rois_insertados[3]) ? $rois_insertados[3] : NULL,
                                        isset($rois_insertados[4]) ? $rois_insertados[4] : NULL,
                                        isset($rois_insertados[5]) ? $rois_insertados[5] : NULL,
                                        isset($rois_insertados[6]) ? $rois_insertados[6] : NULL,
                                        isset($rois_insertados[7]) ? $rois_insertados[7] : NULL,
                                        $usuario
                                    );

                                    if(!$id_rama){
                                        throw new Exception('Error al Registrar las Materias adicionales.');
                                    }
                                }
                            }

    
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
        $isdroptime     = $matriculacion_model->checkTodayisdrop($today); //print_r( $result_process[0]['pla_id']);die();

        if (count($result_process) > 0) {
            /* Exist a register process */
            $pla_id = $result_process[0]['pla_id'];
            $num_min = 2;
            $num_max = 6;
            $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
            if (count($resultIdPlanificacionEstudiante) > 0) {
                /*                 * Exist a register of planificacion_estudiante */
                $pes_id = $resultIdPlanificacionEstudiante[0]['pes_id'];
                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
		        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
		
                $modelPlaEst = PlanificacionEstudiante::findOne($pes_id);

                /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
                $pla_id_est = $modelPlaEst->pla_id;
                $result_pago = RegistroPagoMatricula::checkPagoEstudiante($per_id, $pla_id_est);
                
                // if (count($result_pago) < 1) {
                if (count($result_pago)<0) {
                    // print_r($result_pago);
                    // print_r($per_id);
                    // die();
                    $mod_estudiante  = new Especies();   
                    $datosEstudiante = $mod_estudiante->consultaDatosEstudiante($per_id); 

                    $est_id = $datosEstudiante['est_id'];
                    $mod_id = $datosEstudiante['mod_id'];
                    $data_planificacion_pago = Matriculacion::getPlanificacionPago($mod_id);  

                    $mod_fpago = new FormaPago();
                    $arr_forma_pago = $mod_fpago->consultarFormaPagosaldo();
                    $arr_bancos = $mod_fpago->consultarBancos();

                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Usted ya registro el pago para este periodo."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    /*return $this->redirect('carga-pago', [
                        "validacion" => true,
                        "mensaje" => "Usted no registra pago de matricula previo al registro de materias.",
                        "data_planificacion_pago" => $data_planificacion_pago,
                        //"pla_id" => $data_planificacion_pago['pla_id'],
                        "per_id" => $per_id,
                        //"arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                        //"arr_bancos" => ArrayHelper::map($arr_bancos, "id", "value"),
                    ]);*/

                    return $this->redirect(['matriculacion/registropago', 'per_id' => Yii::$app->request->get('per_id', base64_encode(Yii::$app->session->get("PB_perid")))]);
                }
                /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

                if ($data_student) {
                    $RegistroOnline = RegistroOnline::find()->select("ron_id")->where(["per_id" => $per_id, "pes_id" => $pes_id ])->asArray()->all();
                    
                    if(empty($RegistroOnline)){
                        $ron_id = 0;
                    }
                    else{
                        $ron_id = $RegistroOnline[0]['ron_id']; 
                    }

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
                    ]);//print_r($dataProvider);die();
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
                    $numSubjAca = $this->limitSubject[$unidadAcade];
                    if (count($dataPlanificacion) <= 8) {
                        //$num_min = count($dataPlanificacion);
                        //$num_max = count($dataPlanificacion);
                        $num_min = $numSubjAca['min'];
                        $num_max = (count($dataPlanificacion) <= $numSubjAca['max']) ? count($dataPlanificacion) : $numSubjAca['max'];
                    } else {
                        $num_min = 2;
                    }

                    $estudiante_model = new Estudiante();
                    $est_array        = $estudiante_model-> getEstudiantexperid($per_id);
                    $est_id           = $est_array['est_id'];
                    $paca_id          = $data_student['paca_id'];
                    //$scholarship      = $estudiante_model->isScholarship($est_id,$paca_id);
                    //$isscholar        = $scholarship['bec_id'];     

                    \app\models\Utilities::putMessageLogFile("gastos ". $gastos_administrativos_valor);
                     //per_id pla_id , pes_id
                     
                     
                     
                    $registro_model = new RegistroOnline();
                    $ronned    = $registro_model-> getcurrentRon($per_id);
                    $isschedule        = $ronned[0]['ronid']; 
                    
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

		    // Si tiene este objeto, quiere decir que no ha realizado el último pago
                    $rama = RegistroAdicionalMaterias::find()->where(['ron_id' => $ron_id, 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' => $paca_id, 'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();
                    $pagado = !isset($rama) || $rama['roi_id_1'] == NULL;
                     
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
                                "pagado" => $pagado,
                                "num_min" => $num_min,
                                "num_max" => $num_max,
                                
                                
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

            $pla_id = $result_process[0]['pla_id'];
            $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
            if (count($resultIdPlanificacionEstudiante) > 0) {
                /*                 * Exist a register of planificacion_estudiante */
                $pes_id = $resultIdPlanificacionEstudiante[0]['pes_id'];
                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
		        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
		
                $modelPlaEst = PlanificacionEstudiante::findOne($pes_id);

                /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
                $pla_id_est = $modelPlaEst->pla_id;
                $result_pago = RegistroPagoMatricula::checkPagoEstudiante($per_id, $pla_id_est);
                
                // if (count($result_pago) < 1) {
                if (count($result_pago) < 0) {
                    // print_r($result_pago);
                    // print_r($per_id);
                    // die();
                    $mod_estudiante  = new Especies();   
                    $datosEstudiante = $mod_estudiante->consultaDatosEstudiante($per_id); 

                    $est_id = $datosEstudiante['est_id'];
                    $mod_id = $datosEstudiante['mod_id'];
                    $data_planificacion_pago = Matriculacion::getPlanificacionPago($mod_id);  

                    $mod_fpago = new FormaPago();
                    $arr_forma_pago = $mod_fpago->consultarFormaPagosaldo();
                    $arr_bancos = $mod_fpago->consultarBancos();

                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Usted ya registro el pago para este periodo."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return $this->render('carga-pago', [
                        "validacion" => true,
                        "mensaje" => "Usted no registra pago de matricula previo al registro de materias.",
                        "data_planificacion_pago" => $data_planificacion_pago,
                        "pla_id" => $data_planificacion_pago['pla_id'],
                        "per_id" => $per_id,
                        "arr_forma_pago" => ArrayHelper::map($arr_forma_pago, "id", "value"),
                        "arr_bancos" => ArrayHelper::map($arr_bancos, "id", "value"),
                    ]);
                }
            }

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

    public function actionRegistrodetalle($uper_id=Null){
        
        $per_id = $uper_id ? $uper_id:Yii::$app->session->get("PB_perid");
        // $usu_id = Yii::$app->session->get("PB_iduser");
        // $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
            

                $matriculacion_model = new Matriculacion();

                $today = date("Y-m-d H:i:s");
                $result_process = $matriculacion_model->checkToday($today);
                $rpla_id = $result_process[0]['pla_id'];
                $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $rpla_id);
                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id']; // Por cosas de códigos se necesita hacer todo eso para agarrar el verdadero pla_id
                $result_periodo=$matriculacion_model->checkPeriodo($pla_id);
                $paca_id=$result_periodo[0]['paca_id'];
                

                $pes_id = PlanificacionEstudiante::find()->where(['per_id' => $per_id,'pla_id' => $pla_id, 'pes_estado' => 1, 'pes_estado_logico' => 1])->asArray()->one();
                $ron = RegistroOnline::find()->where(['per_id' => $per_id,'pes_id' => $pes_id['pes_id'], 'ron_estado' => 1, 'ron_estado_logico' => 1])->asArray()->one();
                $roi = RegistroOnlineItem::find()->where(['ron_id' => $ron['ron_id'], 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                $valor_total = 0;
                // Colocar sólo aquellas materias que se encuentran en la tabla de registro adicional materias
                $materias_data_arr = [];
                \app\models\Utilities::putMessageLogFile("per_id: " . $per_id);
                //\app\models\Utilities::putMessageLogFile("ron: " . $ron['ron_id']);
                //\app\models\Utilities::putMessageLogFile("per_id: " . $per_id);
                //\app\models\Utilities::putMessageLogFile("pes_id: " . $pes_id['pes_id']);
                //\app\models\Utilities::putMessageLogFile("pla_id: " .$pla_id);
                //\app\models\Utilities::putMessageLogFile("paca_id: " . $paca_id);
                $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id['pes_id']);
                //\app\models\Utilities::putMessageLogFile("datastudent: " . $data_student['saca_id']);
                // Si se encuentran datos en registro_adicional_materias se debe realizar el cálculo sólo tomando en cuenta esas materias (que son pendientes de pago) y debe aparecer el botón Pagar
                $rama = RegistroAdicionalMaterias::find()->where(['ron_id' => $ron['ron_id'], 'per_id' => $per_id, 'pla_id' => $pla_id,/* 'paca_id' => $paca_id, */'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();
                \app\models\Utilities::putMessageLogFile("rama: " . print_r($rama, true));
                // Las siguientes acciones se realizan sólo si hay registros en la tabla de registro_adicional_materias, pues todas usan del arreglo materias_data_arr
                if(isset($rama)){
                    $roi_IDs = [$rama['roi_id_1'], $rama['roi_id_2'], $rama['roi_id_3'], $rama['roi_id_4'], $rama['roi_id_5'], $rama['roi_id_6']];

                    foreach ($roi as $key => $value) {
                        // Si hay registro en RegistroAdicionalMaterias
                        if(in_array($value['roi_id'], $roi_IDs)){
                            $materias_data_arr[] = [
                                "Subject" => $value['roi_materia_nombre'],
                                "Cost" => $value['roi_costo'],
                                "Code" => $value['roi_materia_cod'],
                                "Block" => $value['roi_bloque'],
                                "Hour" => $value['roi_hora']
                            ];
                            $valor_total += $value['roi_costo'];
                        }
                    }
                }

                // \app\models\Utilities::putMessageLogFile("rama: " . print_r($rama, true));
                // \app\models\Utilities::putMessageLogFile("roi_IDs: " . print_r($roi_IDs, true));
                // \app\models\Utilities::putMessageLogFile("roi: " . print_r($roi, true));
                // \app\models\Utilities::putMessageLogFile("materias_data_arr: " . print_r($materias_data_arr, true));

                $persona = Persona::find()->where(['per_id' => $per_id])->asArray()->one();

                $periodo = (new PeriodoAcademico())->consultarPeriodo($paca_id, true)[0];
                $tipo_semestre = 0;
                $cuotas = 0;

                // Calcular cuál semestre es
                if($periodo['saca_intensivo']){ // Instensivo
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
                    foreach ($materias_data_arr as $key => $value) {
                        $tempBlock[] = $value['Block'];
                    }

                    // \app\models\Utilities::putMessageLogFile("tempBlock: " . print_r($tempBlock, true));

                    $bloque = $tempBlock[0]; // Tomar el primer bloque
                    $cuotas = 3; // Empezar con 3 cuotas

                    // \app\models\Utilities::putMessageLogFile("bloque: " . $bloque);

                    foreach ($tempBlock as $key => $value) { // recorrer la lista de bloques
                        // \app\models\Utilities::putMessageLogFile("IF: " . ($value != $bloque));
                        if($value != $bloque){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                            $cuotas = 6; // Así que las cuotas son 6
                            break; // Salir del foreach
                        }
                        // Si nunca entra al condicional, quiere decir que todas las materias son del mismo bloque
                    }
                }
                // \app\models\Utilities::putMessageLogFile($cuotas);

                // Incluír los gastos administrativos
                if ($data_student['mod_id'] == 1 ){
                    $administrativos='GESTION DE ENTORNOS VIRTUALES';
                    //print_r($administrativos);
                }else{
                    $administrativos = 'GASTOS ADMINISTRATIVOS';
                    // print_r($administrativos);
                }
                 
                $gastos_administrativos = $ron['ron_valor_gastos_pendientes'];
                if($gastos_administrativos > 0){
                    $valor_total += $gastos_administrativos;
                    // Llenar con campos vacíos las olumnas que no tengan datos para que no aparezcan como "(no definido)"
                    $materias_data_arr[] = [
                                            "Subject" => $administrativos, 
                                            "Cost" => $gastos_administrativos,
                                            "Code" => "",
                                            "Block" => "",
                                            "Hour" => "",
                                            ];
                }

                // Si no tiene ni materias pendientes de pago, ni gastos administrativos, quiere decir que el estudiante recién va a empezar el proceso de matriculación, o que ha pagado todo.
                if($valor_total <= 0){
                    // Mandar a la pantalla con $rama NULL para que muestre el mensaje
                    return $this->render('registrodetalle', [
                        "rama" => NULL,
                    ]);
                }

                // \app\models\Utilities::putMessageLogFile("materias_data_arr: " . print_r($materias_data_arr, true));
                \app\models\Utilities::putMessageLogFile("bloque: " . $bloque);
                $valor_unitario = $valor_total / $cuotas;
                $porcentaje = $valor_unitario / $valor_total * 100;

                $porc_mayor = round($porcentaje, 2);
                $por_menor = 100 - ($porc_mayor * ($cuotas - 1));

                // Si son dos cuotas
                if($cuotas == 2){ // Quiere decir que es semestre intensivo B1
                    $fechas_vencimiento = FechasVencimientoPago::find()->where(['saca_id' => $data_student['saca_id'], 'fvpa_bloque' => "B1", 'fvpa_estado' => 1, 'fvpa_estado_logico' => 1])->asArray()->all();
                    // Va a retornar 3 cuotas, así que hay que quitar la primera
                    array_shift($fechas_vencimiento);
                }
                // Si son 3 cuotas
                else if($cuotas == 3){ // Considerar sólo el bloque escogido
                    $fechas_vencimiento = FechasVencimientoPago::find()->where(['saca_id' => $data_student['saca_id'], 'fvpa_bloque' => $bloque, 'fvpa_estado' => 1, 'fvpa_estado_logico' => 1])->asArray()->all();
                }
                else if($cuotas == 5){
                    $fechas_vencimiento = FechasVencimientoPago::find()->where(['saca_id' => $data_student['saca_id'], 'fvpa_estado' => 1, 'fvpa_estado_logico' => 1])->asArray()->all();
                    // Va a retornar 6 cuotas, así que hay que quitar la primera
                    array_shift($fechas_vencimiento);
                }
                else{ // Si son 6 cuotas, se deben tomar las fechas de los dos bloques
                    $fechas_vencimiento = FechasVencimientoPago::find()->where(['saca_id' => $data_student['saca_id'], 'fvpa_estado' => 1, 'fvpa_estado_logico' => 1])->asArray()->all();
                }
                
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

                /*\app\models\Utilities::putMessageLogFile($data_student);
                \app\models\Utilities::putMessageLogFile($persona);
                \app\models\Utilities::putMessageLogFile($materias_data_arr);
                \app\models\Utilities::putMessageLogFile("valor_total: " . $valor_total);
                \app\models\Utilities::putMessageLogFile($cuotas);
                \app\models\Utilities::putMessageLogFile($valor_unitario);
                \app\models\Utilities::putMessageLogFile($porcentaje);
                \app\models\Utilities::putMessageLogFile($arr_pagos);*/

                return $this->render('registrodetalle', [
                    "per_id" => $per_id,
                    "data_student" => $data_student,
                    "persona" => $persona,
                    "valor_total" => $valor_total,
                    "matDataProvider" => $matDataProvider,
                    "pagosDataProvider" => $pagosDataProvider,
                    "pla_id" => $pla_id,
                    "ron_id" => $ron['ron_id'],
                    "rama" => $rama,
                    "cuotas" => $cuotas,
                    "bloque" => $bloque,
                    "saca_id" => $data_student['saca_id'],
                ]);
            //}
        
    }


    public function actionDeletereg() {
       
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
              $roi_id = $data['roi_id'];
              $ron_id = $data['ron_id'];
              $pes_id = $data['pes_id'];
              $per_id = $data['per_id'];
              $matriculacion_model= new Matriculacion();
              $modelRegOn= RegistroOnline::findOne(['ron_id'=>$ron_id,'per_id'=>$per_id,'pes_id'=>$pes_id,'ron_estado'=>1,'ron_estado_logico'=>1]);
              $modelRegItem = RegistroOnlineItem::findAll(['ron_id' => $ron_id]);
              $pla_id=PlanificacionEstudiante::Find()->where (['pes_id'=>$pes_id,'per_id'=>$per_id])->asArray()->one()['pla_id'];
              $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
              $paca_id=$data_student['paca_id'];
              $arr_sub_cancel=RegistroOnlineItem::find()->where(['roi_id' => $roi_id])->asArray()->one()['roi_materia_cod'];
                $sumMatr = 0;
                foreach ($modelRegItem as $key => $item) {
                    if (($item->roi_id == $roi_id)) {
                        $item->roi_estado = '0';
                        $item->roi_estado_logico = '0';
                        $item->roi_fecha_modificacion = $today;
                        $item->roi_usuario_modifica = $usu_id;
                        if (!$item->save()) {
                            throw new Exception('Error to Update Online Item Register.');
                        }

                        //// Eliminar el roi_id de registro_adicional_materias ////
                        // \app\models\Utilities::putMessageLogFile("---Eliminar el roi_id de registro_adicional_materias---");
                        // Obtener el modelo de rama donde aún no se ha pagado
                        $modelRama = RegistroAdicionalMaterias::findOne(['ron_id' => $ron_id, 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' => $paca_id, 'rama_estado' => 1, 'rama_estado_logico' => 1, 'rpm_id' => NULL]);
                        \app\models\Utilities::putMessageLogFile("modelRama existe: " . isset($modelRama));
                        // Validación por si acaso
                        if (isset($modelRama)) {
                            // Sacar los roi_ids que están presentes
                            $arr_roi_ids = [
                                $modelRama->roi_id_1,
                                $modelRama->roi_id_2,
                                $modelRama->roi_id_3,
                                $modelRama->roi_id_4,
                                $modelRama->roi_id_5,
                                $modelRama->roi_id_6,
                                $modelRama->roi_id_7,
                                $modelRama->roi_id_8,
                            ];

                            \app\models\Utilities::putMessageLogFile("arr_roi_ids INICIO: " . print_r($arr_roi_ids, true));
                            \app\models\Utilities::putMessageLogFile("item->roi_id: " . $item->roi_id);

                            // Encontrar el roi_id igual y removerlo
                            for ($i = 0; $i < count($arr_roi_ids); $i++) {
                                // \app\models\Utilities::putMessageLogFile("COMPARACIÓN: " . ($item->roi_id == $arr_roi_ids[$i]));
                                if (intval($item->roi_id) == intval($arr_roi_ids[$i])) {
                                    unset($arr_roi_ids[$i]);
                                    break;
                                }
                            }

                            \app\models\Utilities::putMessageLogFile("arr_roi_ids DESPUÉS: " . print_r($arr_roi_ids, true));

                            // Reordenar el arreglo
                            $arr_roi_ids = array_values($arr_roi_ids);

                            \app\models\Utilities::putMessageLogFile("arr_roi_ids FINAL: " . print_r($arr_roi_ids, true));

                            // Remplazar los roi_ids del registro del modelo por este nuevo arreglo
                            $modelRama->roi_id_1 = $arr_roi_ids[0];
                            $modelRama->roi_id_2 = $arr_roi_ids[1];
                            $modelRama->roi_id_3 = $arr_roi_ids[2];
                            $modelRama->roi_id_4 = $arr_roi_ids[3];
                            $modelRama->roi_id_5 = $arr_roi_ids[4];
                            $modelRama->roi_id_6 = $arr_roi_ids[5];
                            $modelRama->roi_id_7 = $arr_roi_ids[6];
                            $modelRama->roi_id_8 = $arr_roi_ids[7];

                            // Actualizar
                            if (!$modelRama->save()) {
                                throw new Exception('Error al actualizar los roi_ids de registro_adicional_materias');
                            }

                            //// Reducir los gatos administrativos en caso de que se eliminen materias del B2 quedando sólo de B1 ////

                            // Tomar todas las transacciones realizadas. Pagadas y no pagadas
                            $modelRama = RegistroAdicionalMaterias::findAll(['ron_id' => $ron_id, 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' => $paca_id, 'rama_estado' => 1, 'rama_estado_logico' => 1]);

                            // \app\models\Utilities::putMessageLogFile("modelRama existe: " . isset($modelRama));

                            // Separar en pagadas y no pagadas
                            $pagadas = [];
                            $no_pagadas = [];
                            foreach ($modelRama as $key => $value) {
                                if (isset($value['rpm_id'])) {
                                    $pagadas[] = $value;
                                } else {
                                    $no_pagadas[] = $value;
                                }
                            }

                            // \app\models\Utilities::putMessageLogFile("pagadas: " . print_r($modelRama, true));
                            // \app\models\Utilities::putMessageLogFile("no_pagadas: " . print_r($modelRama, true));

                            // Si no hay materias pagadas, calcular sólo básándose en las pendientes
                            if (count($pagadas) <= 0) {
                                // \app\models\Utilities::putMessageLogFile("---------NO Hay materias pagadas------------");

                                // Sacar los roi_ids
                                $bloques = [
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_1']])->asArray()->one()['roi_bloque'],
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_2']])->asArray()->one()['roi_bloque'],
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_3']])->asArray()->one()['roi_bloque'],
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_4']])->asArray()->one()['roi_bloque'],
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_5']])->asArray()->one()['roi_bloque'],
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_6']])->asArray()->one()['roi_bloque'],
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_7']])->asArray()->one()['roi_bloque'],
                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_8']])->asArray()->one()['roi_bloque'],
                                ];

                                // \app\models\Utilities::putMessageLogFile("bloques: " . print_r($bloques, true));

                                // Sacar el 1er bloque
                                $bloque = $bloques[0];

                                // \app\models\Utilities::putMessageLogFile("bloque: " . $bloque);

                                // Obtener los gastos administrativos
                                $gastos_administrativos_valor = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];

                                // \app\models\Utilities::putMessageLogFile("gastos_administrativos_valor INICIO: " . $gastos_administrativos_valor);

                                foreach ($bloques as $key => $value) {
                                    // Si se encuentran los bloques diferentes, los gastos son el doble
                                    // \app\models\Utilities::putMessageLogFile("value != bloque: " . ($value != $bloque && isset($value)));
                                    if ($value != $bloque && isset($value)) {
                                        $gastos_administrativos_valor = $gastos_administrativos_valor * 2;
                                        break;
                                    }
                                }

                                // \app\models\Utilities::putMessageLogFile("gastos_administrativos_valor FIN: " . $gastos_administrativos_valor);

                                // Guardar en los gastos y pendientes
                                $modelRegOn->ron_valor_gastos_adm = $gastos_administrativos_valor;
                                $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_valor;
                            } else {
                                // Si sí hay pagadas, comparar
                                // \app\models\Utilities::putMessageLogFile("---------SÍ hay materias pagadas------------");
                                // Lista de los bloques pagados
                                $bloques_pagados = [];

                                foreach ($pagadas as $key => $value) {
                                    // Tomar todos los rois de los roi_ids, sean null o no
                                    $roi_1 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_1']])->asArray()->one();
                                    $roi_2 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_2']])->asArray()->one();
                                    $roi_3 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_3']])->asArray()->one();
                                    $roi_4 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_4']])->asArray()->one();
                                    $roi_5 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_5']])->asArray()->one();
                                    $roi_6 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_6']])->asArray()->one();
                                    $roi_7 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_7']])->asArray()->one();
                                    $roi_8 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_8']])->asArray()->one();

                                    // Si se encontró roi, agregar su bloque a la lista de bloques pagados
                                    if (isset($roi_1)) {$bloques_pagados[] = $roi_1['roi_bloque'];}
                                    if (isset($roi_2)) {$bloques_pagados[] = $roi_2['roi_bloque'];}
                                    if (isset($roi_3)) {$bloques_pagados[] = $roi_3['roi_bloque'];}
                                    if (isset($roi_4)) {$bloques_pagados[] = $roi_4['roi_bloque'];}
                                    if (isset($roi_5)) {$bloques_pagados[] = $roi_5['roi_bloque'];}
                                    if (isset($roi_6)) {$bloques_pagados[] = $roi_6['roi_bloque'];}
                                    if (isset($roi_7)) {$bloques_pagados[] = $roi_7['roi_bloque'];}
                                    if (isset($roi_8)) {$bloques_pagados[] = $roi_8['roi_bloque'];}
                                }

                                // \app\models\Utilities::putMessageLogFile("bloques_pagados: " . print_r($bloques_pagados, true));

                                // Tomar el primer bloque de la lista de bloques pagados
                                $bloque_pagado = $bloques_pagados[0];
                                // \app\models\Utilities::putMessageLogFile("bloque_pagado: " . $bloque_pagado);

                                // Obtener los gastos administrativos, y considerarlos como los pendientes por defecto ($150)
                                $gastos_administrativos_pendientes = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];
                                // \app\models\Utilities::putMessageLogFile("gastos_administrativos_pendientes INICIO: " . $gastos_administrativos_pendientes);

                                foreach ($bloques_pagados as $key => $value) {
                                    // Si se encuentran los bloques diferentes, los gastos pendientes son 0
                                    // \app\models\Utilities::putMessageLogFile("value != bloque_pagado: " . ($value != $bloque_pagado));
                                    if ($value != $bloque_pagado) {
                                        $gastos_administrativos_pendientes = 0;
                                        break;
                                    }
                                }

                                // \app\models\Utilities::putMessageLogFile("gastos_administrativos_pendientes FIN: " . $gastos_administrativos_pendientes);

                                // Si los gastos administrativos pendientes son 0, colocarlos en el modelo y hacer que los gastos admin sean 300
                                if ($gastos_administrativos_pendientes <= 0) {
                                    $modelRegOn->ron_valor_gastos_adm = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'] * 2;
                                    $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_pendientes;
                                }
                                // Si no son 0, quiere decir que sólo hay pagadas materias de 1 bloque, por lo que hay que comparar si las pendientes de pago siguen siendo del mismo bloque para que no se le vuelva a cobrar, o del otro bloque para que sí se le cobre los gastos administrativos adicionales
                                else {
                                    // Sacar los roi_ids no pagados
                                    $bloques_no_pagados = [
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_1']])->asArray()->one()['roi_bloque'],
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_2']])->asArray()->one()['roi_bloque'],
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_3']])->asArray()->one()['roi_bloque'],
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_4']])->asArray()->one()['roi_bloque'],
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_5']])->asArray()->one()['roi_bloque'],
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_6']])->asArray()->one()['roi_bloque'],
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_7']])->asArray()->one()['roi_bloque'],
                                        RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_8']])->asArray()->one()['roi_bloque'],
                                    ];

                                    // \app\models\Utilities::putMessageLogFile("bloques_no_pagados: " . print_r($bloques_no_pagados, true));

                                    $gastos_administrativos_pendientes = 0;
                                    foreach ($bloques_no_pagados as $key => $value) {
                                        // Si se encuentra un bloque diferente a los ya pagados, hay valor pendiente de pago. Si todos son los mismos, no hay pendiente
                                        // \app\models\Utilities::putMessageLogFile("value != bloque_pagado: " . ($value != $bloque_pagado && isset($value)));
                                        if ($value != $bloque_pagado && isset($value)) {
                                            $gastos_administrativos_pendientes = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];
                                            break;
                                        }
                                    }

                                    // \app\models\Utilities::putMessageLogFile("gastos_administrativos_pendientes CORRECCION: " . $gastos_administrativos_pendientes);

                                    // Si los gastos administrativos pendientes son 0, quiere decir que materias pagadas y no pagadas son del mismo bloque
                                    if ($gastos_administrativos_pendientes <= 0) {
                                        $modelRegOn->ron_valor_gastos_adm = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];
                                        $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_pendientes;
                                    }
                                    // SI no, son de distintos bloques
                                    else {
                                        $modelRegOn->ron_valor_gastos_adm = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'] * 2;
                                        $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_pendientes;
                                    }
                                }
                            }
                        }
                    } /*else
                            $sumMatr += $item->roi_costo;*/
                } // foreach
                $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);

            } catch (Exception $ex) {
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    'title' => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionAnularregistro(/*$uper_id=Null*/){
        $data = Yii::$app->request->get();
        $transaction = Yii::$app->db_academico->beginTransaction();
        $per_id = (isset($data['per_id']) && $data['per_id'] != "") ? ($data['per_id']) : (Yii::$app->session->get("PB_perid"));
        $usu_id = Yii::$app->session->get("PB_iduser");
        $template = "cancelregister";
        $templateEst = "cancelregisterest";
        $error = false;
        try {
            $matriculacion_model = new Matriculacion();
            $modelPersona = Persona::findOne($per_id);
            $today = date("Y-m-d H:i:s");
            $result_process = $matriculacion_model->checkToday($today);
            if (count($result_process) > 0) {
                $ron_id = $data['ron_id'];
                $modelRegOn = RegistroOnline::findOne(['ron_id' => $ron_id, 'per_id' => $per_id]);
                $pes_id = $modelRegOn->pes_id;
                $modelPes = PlanificacionEstudiante::findOne($pes_id);
                $pla_id = $modelPes->pla_id;
                $result_periodo = $matriculacion_model->checkPeriodo($pla_id);
                $paca_id = $result_periodo[0]['paca_id'];
                $mod_id = Planificacion::find()->where(['pla_id' => $pla_id])->asArray()->one()['mod_id'];
                // Se verifica si hay algun pago realizado o por verificar
                $modelRegPag = RegistroPagoMatricula::findOne(['per_id' => $per_id, 'pla_id' => $pla_id, 'ron_id' => $ron_id, 'rpm_estado' => '1', 'rpm_estado_logico' => '1']);
                if (isset($data['cancelSubject']) && $data['cancelSubject'] = '1') {
                    // si hay un registro de pago se debe tomar en consideracion la anulacion por materias
                    $arr_sub_cancel = explode(";", $data['codes']);

                    \app\models\Utilities::putMessageLogFile('Inicio Proceso cron:' . $arr_sub_cancel[0]);
                    $cod = $arr_sub_cancel[0];

                    //calcula cantidad de materias
                    $tm = count($arr_sub_cancel) - 1;

                    //Obtener cuotas y totales
                    $roc_model = new RegistroOnlineItem();
                    $facturas_model = new FacturasPendientesEstudiante();
                    $cuotas_model = new RegistroOnlineCuota();
                    $today = date("Y-m-d H:i:s");
                    $recalculo = $cuotas_model->recalcCuotas($ron_id);
                    $pago = $cuotas_model->totalCuotas($ron_id);
                    // $costo_mat = $roc_model->getCostobyRon($ron_id,$cod);
                    // $tm = $tm * $costo_mat[0]['roi_costo'] ;

                    $i = 0;
                    $costo_mat = 0;
                    $existrpm = 0;
                    \app\models\Utilities::putMessageLogFile('Codigos de materias');
                    \app\models\Utilities::putMessageLogFile(print_r($arr_sub_cancel, true));
                    //foreach($arr_sub_cancel as $arr_sub_cancel ){
                    for ($i = 0; $i < count($arr_sub_cancel) - 1; $i++) {
                        $cod = $arr_sub_cancel[$i];
                        \app\models\Utilities::putMessageLogFile('codigo  MAT: ' . $cod);
                        \app\models\Utilities::putMessageLogFile('codigo RON_ID: ' . $ron_id);

                        $mat = $roc_model->getCostobyRon($ron_id, $cod);
                        $costo_mat += $mat[0]['roi_costo'];
                        // $i++;
                        //Validar que si tiene pago la matria a eliminar no recalcular
                        $res_rpm_item = $roc_model->verificarPagoMateriaByRegistroOnLine($ron_id, $cod);
                        \app\models\Utilities::putMessageLogFile('Resultado de contar pago de materia: ' . $res_rpm_item['exist_rpm']);
                        if ($res_rpm_item['exist_rpm'] > 0) {
                            $existrpm++;
                        }
                    }
                    \app\models\Utilities::putMessageLogFile('totaldesc:' . $costo_mat);
                    \app\models\Utilities::putMessageLogFile('recalculo:' . count($recalculo));

                    \app\models\Utilities::putMessageLogFile('Existen registros de pago matricula: ' . $existrpm);

                    /**
                    DZM analista desarrollo03 proceso  recalculo de  las cuotas pendientes
                     */
                    //Obtenemos los saldos pendientes del estudiantes.
                    if ($recalculo[0]['Cant'] > 0 && $existrpm > 0) {
                        $total_anul_costo = $costo_mat; // costo total de anulacion
                        $fpe_saldo_pendiente = $cuotas_model->getcurrentSaldoPersona($per_id);
                        //recorrer las facturas pendientes
                        $cant_fpe = count($fpe_saldo_pendiente); // cantidad de fpe
                        $val_desc = $total_anul_costo / $cant_fpe;
                        \app\models\Utilities::putMessageLogFile('1 - Anulacion materia  Cantidad de fpe:' . $val_desc);
                        \app\models\Utilities::putMessageLogFile('1 - Anulacion materia  valor desc fpe:' . $val_desc);
                        $i = 0;
                        foreach ($fpe_saldo_pendiente as $key => $value) {
                            //restar el valor
                            if (!$facturas_model->updateSaldosPendientesByAnulMaterias(
                                $fpe_saldo_pendiente[$i]['fpe_id'], $val_desc, $usu_id)
                            ) {
                                //error
                            }
                            \app\models\Utilities::putMessageLogFile('1 - Anulacion materia Actualiza fpe:' . $fpe_saldo_pendiente[$i]['fpe_id']);

                            $i++;

                        }

                    }

                    $prueba = false;

                    //$resp_persona['existen'] == 0)
                    //Cambiar a saldo pendientes
                    if ($recalculo[0]['Cant'] > 0 && $existrpm > 0 && $prueba) {
                        //recalcular
                        $tm = $costo_mat;
                        $cantcuotas = $recalculo[0]['Cant'];
                        $costcuotas = $recalculo[0]['Costo'];
                        $pagadas = $pago[0]['Cant'];
                        $pagadastot = $pago[0]['Costo'];
                        //actualizar cuotas
                        //actualizar cuotas
                        $change_cuotas = $cuotas_model->updatecancelCuotas($pagadas, $tm, $cantcuotas, $costcuotas, $ron_id);
                        //$facturas_pendientes = $cuotas_model->getcurrentCuotas($ron_id);
                        $facturas_pendientesnew = $facturas_model->getcurrentCuotas($per_id);
                        $fpe_pendientesnew = $facturas_model->getcurrentId($per_id);

                        // foreach ($facturas_pendientes as $key => $value) {
                        //$facturas_model = new FacturasPendientesEstudiante();
                        //$inserta_f = $facturas_model->insertarFacturacancel($promocion, $asi_id);

                        //  }
                        $cantcuotasf = $facturas_pendientesnew[0]['Cantf'];
                        /*
                        \app\models\Utilities::putMessageLogFile('valor pendiente:' . $facturas_pendientesnew[0]['Costof']);
                        \app\models\Utilities::putMessageLogFile('cantidad cuotas:' . $cantcuotasf);
                        */
                        $costcuotasf = $facturas_pendientesnew[0]['Costof'] - $tm;
                        /*
                        \app\models\Utilities::putMessageLogFile('costo cuotas:' . $costcuotasf);
                        \app\models\Utilities::putMessageLogFile('fpeid:' . $fpe_pendientesnew[0]['fpeid']);
                        \app\models\Utilities::putMessageLogFile('fpeid:' . $fpe_pendientesnew[1]['fpeid']);
                        \app\models\Utilities::putMessageLogFile('fpeid:' . $fpe_pendientesnew[2]['fpeid']);
                        \app\models\Utilities::putMessageLogFile('fpeid:' . $fpe_pendientesnew[3]['fpeid']);
                        */
                        // for($i=0; $i<count($facturas_pendientesnew); $i++){
                        $i = 0;
                        foreach ($fpe_pendientesnew as $key => $value) {
                            //$facturas_model = new FacturasPendientesEstudiante();
                            //$datafpeid= $datafpeid+1;
                            $datafpeid = $fpe_pendientesnew[$i]['fpeid']; //registro online cuota
                            // $existefactura = $facturas_model->getcurrentFacturas($dataid);
                            //  if  (count($existefactura) > 0) {
                            //$inserta_f = $facturas_model->insertarFacturacancel($datafpeid, $dataid, $datacuota, $datacosto);
                            $inserta_fact = $facturas_model->insertarnewFacturacancel($datafpeid, $cantcuotasf, $costcuotasf);
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
                        $modelCancelReg->paca_id = $paca_id;
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
                            /*$modelEnroll = EnrolamientoAgreement::findAll(['ron_id' => $ron_id, 'per_id' => $per_id]);
                            if($modelEnroll){
                                foreach($modelEnroll as $key2 => $item){
                                    $item->eagr_estado = '0';
                                    $item->eagr_estado_logico = '0';
                                    $item->eagr_fecha_modificacion = $today;
                                    if(!$item->save()){
                                        throw new Exception('Error to Update Enroll Agreement.');
                                    }
                                }
                            }*/
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

                                    //// Eliminar el roi_id de registro_adicional_materias ////
                                    
                                    // \app\models\Utilities::putMessageLogFile("---Eliminar el roi_id de registro_adicional_materias---");

                                    // Obtener el modelo de rama donde aún no se ha pagado
                                    $modelRama = RegistroAdicionalMaterias::findOne(['ron_id' => $ron_id, 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' => $paca_id, 'rama_estado' => 1, 'rama_estado_logico' => 1, 'rpm_id' => NULL]);
                                    // \app\models\Utilities::putMessageLogFile("modelRama existe: " . isset($modelRama));

                                    // Validación por si acaso
                                    if(isset($modelRama)){
                                        // Sacar los roi_ids que están presentes
                                        $arr_roi_ids = [
                                            $modelRama->roi_id_1,
                                            $modelRama->roi_id_2,
                                            $modelRama->roi_id_3,
                                            $modelRama->roi_id_4,
                                            $modelRama->roi_id_5,
                                            $modelRama->roi_id_6,
                                            $modelRama->roi_id_7,
                                            $modelRama->roi_id_8,
                                        ];

                                        // \app\models\Utilities::putMessageLogFile("arr_roi_ids INICIO: " . print_r($arr_roi_ids, true));

                                        // \app\models\Utilities::putMessageLogFile("item->roi_id: " . $item->roi_id);

                                        // Encontrar el roi_id igual y removerlo
                                        for ($i = 0; $i < count($arr_roi_ids); $i++) {
                                            // \app\models\Utilities::putMessageLogFile("COMPARACIÓN: " . ($item->roi_id == $arr_roi_ids[$i]));
                                            if(intval($item->roi_id) == intval($arr_roi_ids[$i])){
                                                unset($arr_roi_ids[$i]);
                                                break;
                                            }
                                        }

                                        // \app\models\Utilities::putMessageLogFile("arr_roi_ids DESPUÉS: " . print_r($arr_roi_ids, true));

                                        // Reordenar el arreglo
                                        $arr_roi_ids = array_values($arr_roi_ids);

                                        // \app\models\Utilities::putMessageLogFile("arr_roi_ids FINAL: " . print_r($arr_roi_ids, true));

                                        // Remplazar los roi_ids del registro del modelo por este nuevo arreglo
                                        $modelRama->roi_id_1 = $arr_roi_ids[0];
                                        $modelRama->roi_id_2 = $arr_roi_ids[1];
                                        $modelRama->roi_id_3 = $arr_roi_ids[2];
                                        $modelRama->roi_id_4 = $arr_roi_ids[3];
                                        $modelRama->roi_id_5 = $arr_roi_ids[4];
                                        $modelRama->roi_id_6 = $arr_roi_ids[5];
                                        $modelRama->roi_id_7 = $arr_roi_ids[6];
                                        $modelRama->roi_id_8 = $arr_roi_ids[7];

                                        // Actualizar
                                        if(!$modelRama->save()){
                                            throw new Exception('Error al actualizar los roi_ids de registro_adicional_materias');
                                        }

                                        //// Reducir los gatos administrativos en caso de que se eliminen materias del B2 quedando sólo de B1 ////

                                        // Tomar todas las transacciones realizadas. Pagadas y no pagadas
                                        $modelRama = RegistroAdicionalMaterias::findAll(['ron_id' => $ron_id, 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' => $paca_id, 'rama_estado' => 1, 'rama_estado_logico' => 1]);

                                        // \app\models\Utilities::putMessageLogFile("modelRama existe: " . isset($modelRama));

                                        // Separar en pagadas y no pagadas
                                        $pagadas = [];
                                        $no_pagadas = [];
                                        foreach ($modelRama as $key => $value) {
                                            if(isset($value['rpm_id'])){
                                                $pagadas[] = $value;
                                            }
                                            else{
                                                $no_pagadas[] = $value;
                                            }
                                        }

                                        // \app\models\Utilities::putMessageLogFile("pagadas: " . print_r($modelRama, true));
                                        // \app\models\Utilities::putMessageLogFile("no_pagadas: " . print_r($modelRama, true));

                                        // Si no hay materias pagadas, calcular sólo básándose en las pendientes
                                        if(count($pagadas) <= 0){
                                            // \app\models\Utilities::putMessageLogFile("---------NO Hay materias pagadas------------");
                                            
                                            // Sacar los roi_ids
                                            $bloques = [
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_1']])->asArray()->one()['roi_bloque'],
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_2']])->asArray()->one()['roi_bloque'],
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_3']])->asArray()->one()['roi_bloque'],
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_4']])->asArray()->one()['roi_bloque'],
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_5']])->asArray()->one()['roi_bloque'],
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_6']])->asArray()->one()['roi_bloque'],
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_7']])->asArray()->one()['roi_bloque'],
                                                RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_8']])->asArray()->one()['roi_bloque'],
                                            ];

                                            // \app\models\Utilities::putMessageLogFile("bloques: " . print_r($bloques, true));

                                            // Sacar el 1er bloque
                                            $bloque = $bloques[0];

                                            // \app\models\Utilities::putMessageLogFile("bloque: " . $bloque);

                                            // Obtener los gastos administrativos
                                            $gastos_administrativos_valor = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];

                                            // \app\models\Utilities::putMessageLogFile("gastos_administrativos_valor INICIO: " . $gastos_administrativos_valor);

                                            foreach ($bloques as $key => $value) {
                                                // Si se encuentran los bloques diferentes, los gastos son el doble
                                                // \app\models\Utilities::putMessageLogFile("value != bloque: " . ($value != $bloque && isset($value)));
                                                if($value != $bloque && isset($value)){
                                                     $gastos_administrativos_valor = $gastos_administrativos_valor * 2;
                                                     break;
                                                }
                                            }

                                            // \app\models\Utilities::putMessageLogFile("gastos_administrativos_valor FIN: " . $gastos_administrativos_valor);

                                            // Guardar en los gastos y pendientes
                                            $modelRegOn->ron_valor_gastos_adm = $gastos_administrativos_valor; 
                                            $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_valor;
                                        }
                                        else{ // Si sí hay pagadas, comparar
                                            // \app\models\Utilities::putMessageLogFile("---------SÍ hay materias pagadas------------");
                                            // Lista de los bloques pagados
                                            $bloques_pagados = [];

                                            foreach ($pagadas as $key => $value) {
                                            // Tomar todos los rois de los roi_ids, sean null o no
                                            $roi_1 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_1']])->asArray()->one();
                                            $roi_2 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_2']])->asArray()->one();
                                            $roi_3 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_3']])->asArray()->one();
                                            $roi_4 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_4']])->asArray()->one();
                                            $roi_5 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_5']])->asArray()->one();
                                            $roi_6 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_6']])->asArray()->one();
                                            $roi_7 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_7']])->asArray()->one();
                                            $roi_8 = RegistroOnlineItem::find()->where(['roi_id' => $value['roi_id_8']])->asArray()->one();

                                            // Si se encontró roi, agregar su bloque a la lista de bloques pagados
                                            if (isset($roi_1)) {$bloques_pagados[] = $roi_1['roi_bloque'];}
                                            if (isset($roi_2)) {$bloques_pagados[] = $roi_2['roi_bloque'];}
                                            if (isset($roi_3)) {$bloques_pagados[] = $roi_3['roi_bloque'];}
                                            if (isset($roi_4)) {$bloques_pagados[] = $roi_4['roi_bloque'];}
                                            if (isset($roi_5)) {$bloques_pagados[] = $roi_5['roi_bloque'];}
                                            if (isset($roi_6)) {$bloques_pagados[] = $roi_6['roi_bloque'];}
                                            if (isset($roi_7)) {$bloques_pagados[] = $roi_7['roi_bloque'];}
                                            if (isset($roi_8)) {$bloques_pagados[] = $roi_8['roi_bloque'];}
                                        }

                                            // \app\models\Utilities::putMessageLogFile("bloques_pagados: " . print_r($bloques_pagados, true));

                                            // Tomar el primer bloque de la lista de bloques pagados
                                            $bloque_pagado = $bloques_pagados[0];
                                            // \app\models\Utilities::putMessageLogFile("bloque_pagado: " . $bloque_pagado);

                                            // Obtener los gastos administrativos, y considerarlos como los pendientes por defecto ($150)
                                            $gastos_administrativos_pendientes = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];
                                            // \app\models\Utilities::putMessageLogFile("gastos_administrativos_pendientes INICIO: " . $gastos_administrativos_pendientes);

                                            foreach ($bloques_pagados as $key => $value) {
                                                // Si se encuentran los bloques diferentes, los gastos pendientes son 0
                                                // \app\models\Utilities::putMessageLogFile("value != bloque_pagado: " . ($value != $bloque_pagado));
                                                if($value != $bloque_pagado){
                                                     $gastos_administrativos_pendientes = 0;
                                                     break;
                                                }
                                            }

                                            // \app\models\Utilities::putMessageLogFile("gastos_administrativos_pendientes FIN: " . $gastos_administrativos_pendientes);

                                            // Si los gastos administrativos pendientes son 0, colocarlos en el modelo y hacer que los gastos admin sean 300
                                            if($gastos_administrativos_pendientes <= 0){
                                                $modelRegOn->ron_valor_gastos_adm = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'] * 2;
                                                $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_pendientes;
                                            }
                                            // Si no son 0, quiere decir que sólo hay pagadas materias de 1 bloque, por lo que hay que comparar si las pendientes de pago siguen siendo del mismo bloque para que no se le vuelva a cobrar, o del otro bloque para que sí se le cobre los gastos administrativos adicionales
                                            else{
                                                // Sacar los roi_ids no pagados
                                                $bloques_no_pagados = [
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_1']])->asArray()->one()['roi_bloque'],
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_2']])->asArray()->one()['roi_bloque'],
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_3']])->asArray()->one()['roi_bloque'],
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_4']])->asArray()->one()['roi_bloque'],
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_5']])->asArray()->one()['roi_bloque'],
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_6']])->asArray()->one()['roi_bloque'],
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_7']])->asArray()->one()['roi_bloque'],
                                                    RegistroOnlineItem::find()->where(['roi_id' => $no_pagadas[0]['roi_id_8']])->asArray()->one()['roi_bloque'],
                                                ];

                                                // \app\models\Utilities::putMessageLogFile("bloques_no_pagados: " . print_r($bloques_no_pagados, true));

                                                $gastos_administrativos_pendientes = 0;
                                                foreach ($bloques_no_pagados as $key => $value) {
                                                    // Si se encuentra un bloque diferente a los ya pagados, hay valor pendiente de pago. Si todos son los mismos, no hay pendiente
                                                    // \app\models\Utilities::putMessageLogFile("value != bloque_pagado: " . ($value != $bloque_pagado && isset($value)));
                                                    if($value != $bloque_pagado && isset($value)){
                                                        $gastos_administrativos_pendientes = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];
                                                        break;
                                                    }
                                                }

                                                // \app\models\Utilities::putMessageLogFile("gastos_administrativos_pendientes CORRECCION: " . $gastos_administrativos_pendientes);

                                                // Si los gastos administrativos pendientes son 0, quiere decir que materias pagadas y no pagadas son del mismo bloque 
                                                if($gastos_administrativos_pendientes <= 0){
                                                    $modelRegOn->ron_valor_gastos_adm = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'];
                                                    $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_pendientes;
                                                }
                                                // SI no, son de distintos bloques
                                                else{
                                                    $modelRegOn->ron_valor_gastos_adm = GastoAdministrativo::find()->where(['mod_id' => $mod_id])->asArray()->one()['gadm_gastos_varios'] * 2;
                                                    $modelRegOn->ron_valor_gastos_pendientes = $gastos_administrativos_pendientes;
                                                }
                                            }
                                        }
                                    }
                                }/*else
                                    $sumMatr += $item->roi_costo;*/
                            } // foreach
                            // $modelRegOn->ron_valor_matricula = $sumMatr;
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


    public function actionRegistroadmin() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $modelEstudiante = Estudiante::findOne(['per_id' => $per_id, 'est_estado' => '1', 'est_estado_logico' => '1']);
        if ($modelEstudiante != NULL) {
            return $this->render('index-out', [
                "message" => Academico::t("matriculacion", "There is no planning information (Registration time)"),
            ]);
        }

        $estudiante_model = new Matriculacion();
        $arr_pla = $estudiante_model->getPlaPeriodoAcademicoActual();
        $data = Yii::$app->request->get();
                
        if ($data['PBgetFilter']) {
            $arrSearch["planificacion"] = $data['planificacion'];
            $arrSearch["admitido"] = $data['admitido'];
            $model = $estudiante_model->consultarEstudiantesPlanificados($arrSearch);
            return $this->renderPartial('newfund-grid', [
                "model" => $model,
            ]);
        } else {
            
            $model = $estudiante_model->consultarEstudiantesPlanificados();
        }
        return $this->render('newfund', [
            'mod_pla' => ArrayHelper::map(array_merge($arr_pla), "id", "nombre"),
            'model' => $model,
        ]);

    } //end function actionRegistroadmin


    public function actionRegistroadminindex($per_id) {
        
        $userperid = Yii::$app->session->get("PB_perid");
        $usuario = Yii::$app->user->identity->usu_id;

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            /*if (Yii::$app->session->get("PB_perid") < 1000) {
                $per_id = $data['per_id'];
            }*/

            try {
                if (isset($data["pes_id"])) {
                    $modelPersona = Persona::findOne($per_id);
                    \app\models\Utilities::putMessageLogFile(1);
                    $modelPlaEst = PlanificacionEstudiante::findOne($data["pes_id"]);
                    $pla_id_est = $modelPlaEst->pla_id;
                    \app\models\Utilities::putMessageLogFile('pla_id_est: '.$pla_id_est);
                    
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
                    \app\models\Utilities::putMessageLogFile("matricula: " . $matricula);
                    $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
                    $pes_id = $data["pes_id"];

                    $modalidad = $data["modalidad"];
                    $carrera = $data["carrera"];
                    $materias = $data["materias"];
                    $matStudent=$matricula['valor'];
                    $dataMaterias = $matriculacion_model->getInfoMallaEstudiante($per_id);
                    $modCode = $modModalidad->getCodeCCostoxModalidad($modelPla->mod_id);
                    $semestre = 0;
                    
                    // Generacion de #Orden
                    $con1 = \Yii::$app->db_facturacion;
                    Secuencias::initSecuencia($con1, 1, 1, 1, "RON", "PAGO REGISTRO ONLINE");
                    $numOrden = str_pad(Secuencias::nuevaSecuencia($con1, 1, 1, 1, 'RON'), 8, "0", STR_PAD_LEFT);
                    
                    $RegistroOnline = RegistroOnline::find()->select("ron_id")->where(["per_id" => $per_id, "pes_id" => $pes_id ])->asArray()->all();
                    
                    if(empty($RegistroOnline)){
                        $registro_online_model = new RegistroOnline();

                        $ron_id = 0;
                    }
                    else
                        $ron_id = $RegistroOnline[0]['ron_id']; 
                        
                        
                    if($ron_id == 0){
                        $id = $registro_online_model->insertRegistroOnline(
                            $per_id, $pes_id, strval($numOrden), strval($modalidad), strval($carrera), strval($semestre), strval($mod_est->est_categoria), 0, 0, 0, $matricula['valor'], 1, $usuario
                        );
                    }else{
                        $id = $ron_id;
                    }
                    
                      
                    if ($id > 0) {
                        $ron_id = $id;//;$registro_online_model->getPrimaryKey();
                        $RegistroOnline=RegistroOnline::find()->select("ron_valor_gastos_adm")->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one();
                        \app\models\Utilities::putMessageLogFile($id);
                        $costoMaterias = 0;
                        $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
                        $dataPlanificacion = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);
                        \app\models\Utilities::putMessageLogFile($dataPlanificacion['Asignatura']);
                        $num_min = 2;
                        $num_max = 6;
                        if (count($dataPlanificacion) <= 2) {
                            $num_min = count($dataPlanificacion);
                            $num_max = count($dataPlanificacion);
                        } else {
                            $num_min = 2;
                        }
                        
                        $contMateria = 0;
                        $bloques = $data["bloque"];
                        $horas   = $data["hora"];
                        $paralelos = $data["paralelo"];
                        
                        $rois_insertados = [];

                        foreach ($materias as $materia) {
                            $costo      = 0;
                            $creditos   = 0;
                            $codMateria = 0;
                            $asignatura = $materia;
                            $bloque     = $bloques[$contMateria];
                            $hora       = $horas[$contMateria];
                            $paralelo   = $paralelos[$contMateria];
                            foreach($dataMaterias as $key => $value){
                                if(trim(strtolower($value['Asignatura'])) == trim(strtolower($materia))){
                                    $asignatura = $value['Asignatura'];
                                    $creditos   = $value['AsigCreditos'];
                                    $codMateria = $value['MallaCodAsig'];
                                    $costo      = $creditos * $value['CostoCredito'];
                                    $totalPago += $costo;
                                }
                            }//foreach

                            $registro_online_item_model = new RegistroOnlineItem();

                            $id_roi = $registro_online_item_model->insertRegistroOnlineItem(
                                $id, strval($codMateria), strval($asignatura), strval($creditos), $costo, strval($bloque), strval($hora), strval($paralelo), $usuario
                            );

                            $rois_insertados[] = $id_roi;

                            $contMateria++;


                        }//foreach

                        //Inicio de validacion de pendiente pagos
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
                            
                            if($block_roi==$bloque){ // Si uno de ellos es diferente, quiere decir que hay más de un bloque
                                 // Así que se divide a la mitad
                                $gastos_pendientes=$gastos_administrativos_valor;
                                $gastos_administrativos_valor = $gastos_administrativos_valor * 1;
                                $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                                //break; // Salir del foreach
                            }else{
                                $gastos_administrativos_valor = $gastos_administrativos_valor * 2;
                                $gastos_pendientes=$gastos_administrativos_valor;
                                $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                            }
                        
                        }else{
                            \app\models\Utilities::putMessageLogFile("1");
                            $RegistroOnlineP=RegistroOnline::find()->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one()['ron_valor_gastos_pendientes'];
                            $RegistroOnlineGastos=RegistroOnline::find()->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->one()['ron_valor_gastos_adm'];
                            if($RegistroOnlineP==0 and $RegistroOnlineGastos>0){
                                $roi_bloque = RegistroOnlineItem::find()->select("roi_bloque")->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                                $gastos_administrativos_valor = GastoAdministrativo::find()->where(['mod_id' => $modalidad])->asArray()->one()['gadm_gastos_varios'];
                                $gastos_registro = RegistroOnline::find()->where(['ron_id' => $id])->asArray()->one()['ron_valor_gastos_adm'];
                                if($gastos_administrativos_valor==$RegistroOnlineGastos){
                                    $gastos_pendientes=0;
                                    $gastos_administrativos_valor = $gastos_administrativos_valor * 2;
                                    $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);

                                }else{
                                    $gastos_administrativos_valor = $gastos_administrativos_valor * 2; // 300
                                    $gastos_pendientes=0; // 0
                                    $ron_gastos=(new RegistroOnline())->insertarActualizacionGastos($id,$gastos_administrativos_valor,$gastos_pendientes);
                                }
                            }
                        }
                        //fin de validacion de pendiente pagos
                        $RegistroAdiconal=RegistroAdicionalMaterias::find()->select("rama_id")->where(["per_id" => $per_id, "ron_id" => $id])->asArray()->all();
                        if (empty($RegistroAdiconal)){
                            $roi_id = RegistroOnlineItem::find()->where(['ron_id' => $id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                            $modelPla = Planificacion::findOne($modelPlaEst->pla_id);
                            $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $modelPla['pla_id']);
                            $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                            \app\models\Utilities::putMessageLogFile("ron: " . $id);
                            \app\models\Utilities::putMessageLogFile("pla: " . $pla_id);
                            $result_periodo=$matriculacion_model->checkPeriodo($pla_id);
                            $paca_id=$result_periodo[0]['paca_id'];
                            \app\models\Utilities::putMessageLogFile("paca: " . $paca_id);
                            //$paca_id=$modelPla['paca_id'];
                            $registro_adicional_materias_model  = new RegistroAdicionalMaterias();
                            $RegistroAdd=RegistroAdicionalMaterias::find()->select("rpm_id")->where(["per_id" => $per_id, "ron_id" => $id ])->asArray()->all();
                            if(empty($RegistroAdd['rpm_id'])){
                                $id_rama = $registro_adicional_materias_model->insertRegistroAdicionalMaterias(
                                    $id, $per_id, $pla_id, $paca_id,
                                    $roi_id['0']['roi_id'],
                                    $roi_id['1']['roi_id'],
                                    $roi_id['2']['roi_id'],
                                    $roi_id['3']['roi_id'],
                                    $roi_id['4']['roi_id'],
                                    $roi_id['5']['roi_id'],
                                    $roi_id['6']['roi_id'],
                                    $roi_id['7']['roi_id'],
                                    $usuario

                                );
                            }
                        } else{
                            $RegistroAdicionalMaterias = RegistroAdicionalMaterias::find()->where(['ron_id' => $id, 'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();

                            if(isset($RegistroAdicionalMaterias)){
                                $i = 0;
                                for ($x=0; $x < 8; $x++) { 
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
                                $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $modelPla['pla_id']);
                                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                                $result_periodo = $matriculacion_model->checkPeriodo($pla_id);
                                $paca_id = $result_periodo[0]['paca_id'];
                                
                                $id_rama = (new RegistroAdicionalMaterias())->insertRegistroAdicionalMaterias(
                                    $id, $per_id, $pla_id, $paca_id,
                                    isset($rois_insertados[0]) ? $rois_insertados[0] : NULL,
                                    isset($rois_insertados[1]) ? $rois_insertados[1] : NULL,
                                    isset($rois_insertados[2]) ? $rois_insertados[2] : NULL,
                                    isset($rois_insertados[3]) ? $rois_insertados[3] : NULL,
                                    isset($rois_insertados[4]) ? $rois_insertados[4] : NULL,
                                    isset($rois_insertados[5]) ? $rois_insertados[5] : NULL,
                                    isset($rois_insertados[6]) ? $rois_insertados[6] : NULL,
                                    isset($rois_insertados[7]) ? $rois_insertados[7] : NULL,
                                    $usuario
                                );

                                if(!$id_rama){
                                    throw new Exception('Error al Registrar las Materias adicionales.');
                                }
                            }
                        }
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    } else {
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
        $today = date("Y-m-d H:i:s");
        $result_process = $matriculacion_model->checkToday($today);
        $isdroptime = $matriculacion_model->checkTodayisdrop($today);

        $usu_id = Yii::$app->session->get("PB_iduser");
        $mod_usuario = Usuario::findIdentity($usu_id);

        if (count($result_process) > 0) {
        
            /*             * Exist a register process */
            $rco_id = $result_process[0]['rco_id'];
            $rco_num_bloques = $result_process[0]['rco_num_bloques'];
            $pla_id = $result_process[0]['pla_id'];
            $num_min = 2;
            $num_max = 6;
            $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
            if (count($resultIdPlanificacionEstudiante) > 0) {
                /*                     * Exist a register of planificacion_estudiante */
                $pes_id = $resultIdPlanificacionEstudiante[0]['pes_id'];
                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                $resultRegistroOnline = $matriculacion_model->checkPlanificacionEstudianteRegisterConfiguracion($per_id, $pes_id, $pla_id);
                if (count($resultRegistroOnline) > 0) {
                    $ron_id=$resultRegistroOnline['ron_id'];
                    if ($ron_id!=Null){
                        return $this->render('findexreg', [
                                //"planificacion" => $dataProvider,
                                "data_student" => $data_student,
                                "num_min" => $num_min,
                                "num_max" => $num_max,
                                "pes_id" => $pes_id,
                                "per_id" => $per_id,
                        ]);
                    }
                   
                   
                   
                } else {
                    $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
                    $dataRegistration    = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id,0);
                    $dataRegRs           = ArrayHelper::map($dataRegistration, "roi_id", "Code");
                    $dataPlanificacion   = $matriculacion_model->getAllDataPlanificacionEstudiante($per_id, $pla_id);
                    $materiasxEstudiante = PlanificacionEstudiante::findOne($pes_id);
                    $unidadAcade = strtolower($data_student['pes_jornada']); 
                    $numSubjAca = $this->limitSubject[$unidadAcade];
                    if (count($dataPlanificacion) <= 8) {
                        //$num_min = count($dataPlanificacion);
                        //$num_max = count($dataPlanificacion);
                        $num_min = $numSubjAca['min'];
                        $num_max = (count($dataPlanificacion) <= $numSubjAca['max'])?count($dataPlanificacion):$numSubjAca['max'];
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
                    $hasSubject = (count($dataPlanificacion) == count($dataRegRs))?false:true;
                    $howmuchSubject = count($dataRegRs);
                    $costo       = $matriculacion_model->getCostFromRegistroOnline($ron_id);
                    $registro_add= $matriculacion_model->getRegistroAdiciOnline($ron_id);
                    
                    $estudiante_model = new Estudiante();
                    $est_array        = $estudiante_model-> getEstudiantexperid($per_id);
                    $est_id           = $est_array['est_id'];
                    $paca_id          = $data_student['paca_id'];
                    //$scholarship = $estudiante_model->isScholarship($est_id,$paca_id);
                    //$isscholar=$scholarship['bec_id'];     
 
                    $registro_model = new RegistroOnline();
                    $ronned    = $registro_model-> getcurrentRon($per_id);
                    $isschedule        = $ronned[0]['ronid']; 
                      
                    if($data_student['mod_id']=='1'){
                        $gastoAdm=50; 
                        $dataMat['VARIOS']=$gastoAdm;
                    } else if ($data_student['mod_id']=='2') {
                        $gastoAdm=300; 
                        $dataMat['VARIOS']=$gastoAdm;
                        
                    } else if ($data_student['mod_id']=='3') {
                        $gastoAdm=300;
                        $dataMat['VARIOS']=$gastoAdm;
                        
                    } else if ($data_student['mod_id']=='4') {
                        $gastoAdm=300;
                        $dataMat['VARIOS']=$gastoAdm;
                        
                    } else {
                        $gastoAdm=0;
                        $dataMat['VARIOS']=$gastoAdm;
                        
                    }
                    
                        
                    $rama = RegistroAdicionalMaterias::find()->where(['ron_id' => $ron_id, 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' => $paca_id, 'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();
                    $pagado = !isset($rama) || $rama['roi_id_1'] == NULL;
                     
                    return $this->render('findex', [
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
                                "pagado" => $pagado,
                                "num_min" => $num_min,
                                "num_max" => $num_max
                                
                                
                    ]);
              
                 }
                
                
            } else {
             
               
               
               
                if ($userperid == $per_id) { 
                    return $this->render('index-out', [
                        "message" => Academico::t("matriculacion", "There is no planning information (Registration time)"),
                    ]);
                }else {
                    return $this->render('index-out', [
                        "message" => Academico::t("matriculacion", "Usted ya registro planificacion de este estudiante"),
                    ]);
                }
                            
            }
        } else {
            return $this->redirect('registro');
        }
    }
}
