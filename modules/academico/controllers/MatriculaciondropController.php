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
use app\modules\academico\models\CancelacionRegistroOnline;
use app\modules\academico\models\CancelacionRegistroOnlineItem;
use app\modules\academico\models\EnrolamientoAgreement;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\EstudiantePagoCarrera;
use app\modules\academico\models\Planificacion;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\RegistroOnline;
use app\modules\academico\models\RegistroOnlineCuota;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\ProgramaCostoCredito;
use app\modules\academico\models\RegistroAdicionalMaterias;
use app\modules\academico\models\RegistroOnlineItem;
use app\modules\academico\models\RegistroPagoMatricula;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\modules\academico\Module as Academico;
use app\modules\financiero\models\FormaPago;
use app\modules\financiero\models\FacturasPendientesEstudiante;

Academico::registerTranslations();


class MatriculaciondropController extends \app\components\CController {

    public $limitSubject = [
        'asociado' => ["min" => 2, "max" => 6], //Asocciate
        'licenciatura' => ["min" => 2, "max" => 6], //Bachelor
        'master' => ["min" => 2, "max" => 4], //Masters
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

    /**
     * Function controller to /matriculacion/index
     * @author
     * @param
     * @return
     */
     

    /**
     * Function controller to /matriculacion/registro
     * @author -
     * @param
     * @return
     */
    public function actionIndex() { // pantalla para que el estudiante seleccione las materias a registrarse
        $per_id = Yii::$app->session->get("PB_perid");
        $_SESSION['JSLANG']['You must choose at least one Subject to Cancel Registration'] = Academico::t('matriculacion', 'You must choose at least one Subject to Cancel Registration');
        $_SESSION['JSLANG']['You must choose at least a number or subjects '] = Academico::t('matriculacion', 'You must choose at least a number or subjects ');
        $_SESSION['JSLANG']['You must choose at least one'] = Academico::t('matriculacion', 'You must choose at least one');
        $_SESSION['JSLANG']['The number of subject that you can cancel is '] = Academico::t('matriculacion', 'The number of subject that you can cancel is ');

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["pes_id"])) {
                $modelPersona = Persona::findOne($per_id);
                $modelEstudiante = Estudiante::findOne(['per_id' => $per_id, 'est_estado' => '1', 'est_estado_logico' => '1']);
                $data_estudio = Matriculacion::getEstudioAcademicoByEstudiante($per_id);
                $modelPes = PlanificacionEstudiante::findOne($data["pes_id"]);
                $eaca_id = $data_estudio['id'];
                $mod_id = $data_estudio['mod_id'];
                $pes_id = $data["pes_id"];
                $modalidad = $data["modalidad"];
                $carrera = $data["carrera"];
                $materias = $data["materias"];
                $codes = $data["codes"];
                $creditos = $data["credits"];
                $ron_id = "";
                $eventMatUp = false;
                $registro_online_model = new RegistroOnline();
                $modelAd = $modReg = null;
                if (isset($data['registerSubject']) && $data['registerSubject'] == 1){
                    $ron_id = $data["ron_id"];
                    $model_rpm = RegistroPagoMatricula::findOne(['per_id' => $per_id, 'pla_id' => $modelPes->pla_id, 'ron_id' => $ron_id, 'rpm_estado' => '1', 'rpm_estado_logico' => '1']);
                    if(!$model_rpm){
                        $modReg = RegistroAdicionalMaterias::findOne(['per_id' => $per_id, 'ron_id' => $ron_id, 'rama_estado' => '1', 'rama_estado_logico' => '1']);
                        $modReg->rama_estado_logico = '0';
                        $modReg->rama_estado = '0';
                        $modReg->rama_fecha_modificacion = date(Yii::$app->params['dateTimeByDefault']);
                        if (!$modReg->save()) {
                            throw new Exception('Error en Registro Online.');
                        }
                    }
                }else{
                    $tmpModelRon = RegistroOnline::findOne(['per_id' => $per_id, 'pes_id' => $pes_id, 'ron_estado' => '1', 'ron_estado_logico' => '1',]);
                    if($tmpModelRon){
                        throw new Exception('Error en Registro Online ya existe.');
                    }
                    $registro_online_model->per_id = $per_id;
                    $registro_online_model->pes_id = $pes_id;
                    $registro_online_model->ron_num_orden = 0; //Ya no se usa pero no permite null, por eso tiene valor de 0
                    $registro_online_model->ron_anio = date("Y");
                    $registro_online_model->ron_modalidad = $modalidad;
                    $registro_online_model->ron_carrera = $carrera;
                    $registro_online_model->ron_estado_registro = "1"; //Igual esta tampoco ya no se usa
                    $registro_online_model->ron_fecha_registro = date(Yii::$app->params['dateByDefault']);
                    $registro_online_model->ron_estado = "1";
                    $registro_online_model->ron_estado_logico = "1";
                    if ($registro_online_model->save()) {
                        $ron_id = $registro_online_model->getPrimaryKey();
                    }else{
                        throw new Exception('Error en Registro Online.');
                    }
                }

                $modelPla = Planificacion::findOne($modelPes->pla_id);
                $registro_online_model = RegistroOnline::findOne($ron_id);
                $modelAd = new RegistroAdicionalMaterias();
                $modelAd->ron_id = $ron_id;
                $modelAd->per_id = $per_id;
                $modelAd->pla_id = $modelPes->pla_id;
                $modelAd->paca_id = $modelPla->paca_id;
                $modelAd->rama_estado = '1';
                $modelAd->rama_estado_logico = '1';
                if($modReg){
                    $modelAd->roi_id_1 = $modReg->roi_id_1;
                    $modelAd->roi_id_2 = $modReg->roi_id_2;
                    $modelAd->roi_id_3 = $modReg->roi_id_3;
                    $modelAd->roi_id_4 = $modReg->roi_id_4;
                    $modelAd->roi_id_5 = $modReg->roi_id_5;
                    $modelAd->roi_id_6 = $modReg->roi_id_6;
                }
                $modelAd->rama_fecha_creacion = date(Yii::$app->params['dateTimeByDefault']);
                if (!$modelAd->save()) {
                    throw new Exception('Error en Registro Online.');
                }

                $cont = 0;
                $costoPrograma = ProgramaCostoCredito::findOne(['eaca_id'=> $eaca_id, 'mod_id' => $mod_id,'pccr_estado' => '1', 'pccr_estado_logico' => '1',]);

                foreach ($materias as $materia) {
                    $registro_online_item_model = new RegistroOnlineItem();
                    $registro_online_item_model->ron_id = $ron_id;
                    $registro_online_item_model->roi_materia_cod = $codes[$cont];
                    $registro_online_item_model->roi_creditos = $creditos[$cont];
                    $registro_online_item_model->roi_costo = $costoPrograma->pccr_costo_credito * $creditos[$cont];
                    
                    $registro_online_item_model->roi_materia_nombre = $materia;
                    $registro_online_item_model->roi_estado = "1";
                    $registro_online_item_model->roi_estado_logico = "1";
                    
                    if(!$registro_online_item_model->save()){
                        throw new Exception('Error en Registro Online Cuota.');
                    }
                    
                    if(!isset($modelAd->roi_id_1)){
                        $modelAd->roi_id_1 = $registro_online_item_model->roi_id;
                    }elseif(!isset($modelAd->roi_id_2)){
                        $modelAd->roi_id_2 = $registro_online_item_model->roi_id;
                    }elseif(!isset($modelAd->roi_id_3)){
                        $modelAd->roi_id_3 = $registro_online_item_model->roi_id;
                    }elseif(!isset($modelAd->roi_id_4)){
                        $modelAd->roi_id_4 = $registro_online_item_model->roi_id;
                    }elseif(!isset($modelAd->roi_id_5)){
                        $modelAd->roi_id_5 = $registro_online_item_model->roi_id;
                    }elseif(!isset($modelAd->roi_id_6)){
                        $modelAd->roi_id_6 = $registro_online_item_model->roi_id;
                    }
                    
                    if (!$modelAd->save()) {
                        throw new Exception('Error en Registro Online.');
                    }
                    
                    $cont++;
                }
              
              
                //Send email
                $report = new ExportFile();
                $this->view->title = Academico::t("matriculacion", "Registration"); // Titulo del reporte
                $matriculacion_model = new Matriculacion();
                $plananual_model = new PeriodoAcademico();
                $materiasxEstudiante = PlanificacionEstudiante::findOne($pes_id);
                $data_student = $matriculacion_model->getDataStudenbyRonId($ron_id);
                $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id,0);
                $dataPlananual = $plananual_model->getFechasPeriodoAcademicoActual();
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
                    "0" => $data_student["per_correo"],
                );
                $files = array(
                    "0" => $tmp_path,
                );
                $asunto = Academico::t('matriculacion',"Online Registration");
                $base = Yii::$app->basePath;
                $lang = Yii::$app->language;
                $body = Utilities::getMailMessage("registro", array("[[user]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido, "[[periodo]]" => $data_student["pla_periodo_academico"], "[[modalidad]]" => $data_student["mod_nombre"]), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                $titulo_mensaje = Academico::t('matriculacion',"Online Enrollment Record");

                Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);

                Utilities::removeTemporalFile($tmp_path);

                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                
            }
              
        }

        $matriculacion_model = new Matriculacion();
        $today = date("Y-m-d H:i:s");
        $result_process = $matriculacion_model->checkToday($today);
        $isdroptime = $matriculacion_model->checkTodayisdrop($today);
        if (count($result_process) > 0) {
            /*             * Exist a register process */
            $pla_id = $result_process[0]['pla_id'];
            $resultIdPlanificacionEstudiante = $matriculacion_model->getIdPlanificacionEstudiante($per_id, $pla_id);
            if (count($resultIdPlanificacionEstudiante) > 0) {
                /*                 * Exist a register of planificacion_estudiante */
                $pes_id = $resultIdPlanificacionEstudiante[0]['pes_id'];
                $pla_id = $resultIdPlanificacionEstudiante[0]['pla_id'];
                $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $pes_id);
                if ($data_student) {
                    $ron_id = $data_student["ron_id"];
                    $roi = RegistroOnlineItem::find()->where(['ron_id' => $ron_id, 'roi_estado' => 1, 'roi_estado_logico' => 1])->asArray()->all();
                    $paca_id          = $data_student['paca_id'];
                    $modelRonOn = RegistroOnline::findOne($ron_id);
                    $dataRegistration = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id,0);
                    $dataRegRs = ArrayHelper::map($dataRegistration, "roi_id", "Code");

                    // Colocar sólo aquellas materias que se encuentran en la tabla de registro adicional materias
                    $materias_data_arr = [];

                    // Si se encuentran datos en registro_adicional_materias se debe realizar el cálculo sólo tomando en cuenta esas materias (que son pendientes de pago) y debe aparecer el botón Pagar
                    $rama = RegistroAdicionalMaterias::find()->where(['ron_id' => $ron_id, 'per_id' => $per_id, 'pla_id' => $pla_id, 'paca_id' =>$paca_id , 'rpm_id' => NULL, 'rama_estado' => 1, 'rama_estado_logico' => 1])->asArray()->one();

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
                    }else{
                        // Si no hay materias para pagar, retornar al registro
                        return $this->redirect('/asgard/academico/matriculacion/registro');
                    }

                    //$dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id,0);
                    $materiasxEstudiante = PlanificacionEstudiante::findOne($pes_id);
                    $dataProvider = new ArrayDataProvider([
                        'key' => 'Ids',
                        'allModels' => $materias_data_arr,
                        'pagination' => [
                            'pageSize' => Yii::$app->params["pageSize"],
                        ],
                        'sort' => [
                            'attributes' => ["Subject"],
                        ],
                    ]);
                    $hasSubject = (count($materias_data_arr) == count($dataRegRs))?false:true;
                    if($modelRonOn->ron_estado_cancelacion == '1') {
                     //   Yii::$app->session->setFlash('warning',"<h4>".Yii::t('jslang', 'Warning')."</h4>". Academico::t('matriculacion', 'There is a pending cancellation process.'));
                   $pending=True;
                    }
                    $unidadAcade = strtolower($data_student['pes_jornada']); 
                    $min_cancel = $this->limitCancel[$unidadAcade]['min'];
                     $howelim =count($dataRegRs);
                     
                     
                     
                      $estudiante_model = new Estudiante();
                    $periodo_model = new PeriodoAcademico();
                    $est_array=$estudiante_model-> getEstudiantexperid($per_id);
                    $paca_array=$periodo_model-> getPeriodoAcademicoActual();
                    $est_id = $est_array['est_id'];
                    $paca_id = $paca_array[0]['id'];
                    $scholarship = $estudiante_model->isScholarship($est_id,$paca_id);
                    $isscholar=$scholarship['bec_id'];    

                  
                     if ($isscholar != NULL ) {
                     
                    return $this->render('registro-sch', [
                                "pes_id" => $pes_id,
                                //"hasSubject" => $hasSubject,
                                "registredSuject" => $dataRegRs,
                                 "HowelSuject" => $howelim,
                                "planificacion" => $dataProvider,
                                "data_student" => $data_student,
                                "title" => Academico::t("matriculacion", "Register saved (Record Time)"),
                                "ron_id" => $ron_id,
                                "materiasxEstudiante" => $materiasxEstudiante,
                                "cancelStatus" => $modelRonOn->ron_estado_cancelacion,
                                "plananual" => $dataPlananual,
                                "anularRegistro" => true,
                                "min_cancel" => $min_cancel,
                                "cancelpending" => $pending,
                                 "isdrop" => $isdroptime,
                                 "isreg" => $result_process, 
                    ]);
                    
                    } Else {
                    
                    return $this->render('registro', [
                                "pes_id" => $pes_id,
                                //"hasSubject" => $hasSubject,
                                "registredSuject" => $dataRegRs,
                                 "HowelSuject" => $howelim,
                                "planificacion" => $dataProvider,
                                "data_student" => $data_student,
                                "title" => Academico::t("matriculacion", "Register saved (Record Time)"),
                                "ron_id" => $ron_id,
                                "materiasxEstudiante" => $materiasxEstudiante,
                                "cancelStatus" => $modelRonOn->ron_estado_cancelacion,
                                "plananual" => $dataPlananual,
                                "anularRegistro" => true,
                                "min_cancel" => $min_cancel,
                                "cancelpending" => $pending,
                                 "isdrop" => $isdroptime,
                                 "isreg" => $result_process, 
                    ]);
                    
                    
                    
                    
                     }
                    
                    
                    
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
                //        Yii::$app->session->setFlash('warning',"<h4>".Yii::t('jslang', 'Warning')."</h4>". Academico::t('matriculacion', 'There is a pending cancellation process.'));

                return $this->render('registro', [
                            "planificacion" => $dataProvider,
                            "data_student" => $data_student,
                            "title" => Academico::t("matriculacion", "Last register saved (Non-registration time)"),
                            "ron_id" => $last_ron_id,
                            "cancelStatus" => $modelRonOn->ron_estado_cancelacion,
                            "materiasxEstudiante" => $materiasxEstudiante,
                ]);
            } else {
                /*                 * If not exist a minimal one register in registro_online */
                return $this->render('index-out', [
                            "message" => Academico::t("matriculacion", "There is no information on the last record (Non-registration time)"),
                ]);
            }
        }
    }

    public function actionList() { // listar estudiantes por aprobar matriculacion
        $model = new RegistroPagoMatricula();
        $arr_status = [-2 => Academico::t("matriculacion", "-- Select Status --"), -1 => Academico::t("matriculacion", "Payment Pending"), 0 => Academico::t("matriculacion", "Payment Processing"), 1 => Academico::t("matriculacion", "Payment Processed"), 2 => Academico::t("matriculacion", "Payment not Approved")];

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
                    'arr_carrera' => array_merge([0 => Academico::t("matriculacion", "-- Select Program --")], ArrayHelper::map($arr_carrera, "pes_id", "pes_carrera")),
                    'arr_pla_per' => array_merge([0 => Academico::t("matriculacion", "-- Select Academic Period --")], ArrayHelper::map($arr_pla_per, "pla_id", "pla_periodo_academico")),
                    'arr_modalidad' => array_merge([0 => Academico::t("matriculacion", "-- Select Modality --")], ArrayHelper::map($arr_modalidad, "mod_id", "mod_nombre")),
                    'arr_status' => $arr_status,
        ]);
    }

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
            if($model_can){
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
            }else{
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
            }else{
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

    public function actionUpdatepagoregistro() { // accion para aprobar matriculacion de estudiante
        $data = Yii::$app->request->get();
        $today = date("Y-m-d H:i:s");
        $usu_id = Yii::$app->session->get("PB_iduser");
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
                        header('Content-Disposition: attachment; filename="hojaMatricula_' . time() . '.pdf";');
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
                $model_registroOnlines = RegistroOnline::findOne($data["id_ron"]);
                $model_planificacionEst = PlanificacionEstudiante::findOne($model_registroOnlines->pes_id);
                $model_registro_pago_matricula = RegistroPagoMatricula::findOne($data["id_rpm"]);
                if($data["estado"] == 1)
                    $model_registro_pago_matricula->rpm_estado_generado = '2'; // si esta aprobado /**0->por pagar, 1->por revisar, 2->pagado */
                else
                    $model_registro_pago_matricula->rpm_estado_generado = '0'; // se coloca nuevamente estado de por pagar 
                $model_registro_pago_matricula->rpm_estado_aprobacion = $data["estado"]; // si esta aprobado /** 0->No revisado, 1->Aprobado, 2->Rechazado */
                if($data["file"] != "" && $data["file"] != ".")
                    $model_registro_pago_matricula->rpm_hoja_matriculacion = $data["file"];
                $model_registro_pago_matricula->rpm_usuario_apruebareprueba = $usu_id;
                $model_registro_pago_matricula->rpm_observacion = $data["observacion"];
                $model_registro_pago_matricula->rpm_fecha_transaccion = $today;
                $model_registro_pago_matricula->rpm_fecha_modificacion = $today;
                $model_registro_pago_matricula->rpm_usuario_modifica = $usu_id;
                $modelPersona = Persona::findOne($model_registroOnlines->per_id);

                if ($model_registro_pago_matricula->save()) {
                    $matriculacion_model = new Matriculacion();
                    $data_student = $matriculacion_model->getDataStudenbyRonId($data["id_ron"]);
                    $template = ($data["estado"] == 1)?"generado":"pagonegado";

                    $body = Utilities::getMailMessage($template, array(
                                "[[user]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido,
                                "[[periodo]]" => $data_student["pla_periodo_academico"],
                                "[[modalidad]]" => $data_student["mod_nombre"]
                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    $titulo_mensaje = Academico::t('matriculacion',"Online Enrollment Registration Confirmation");

                    $from = Yii::$app->params["adminEmail"];
                    $to = array(
                        "0" => $data_student["per_correo"],
                    );                   
                    $files = array();
                    $asunto = Academico::t('matriculacion',"Online Registration");

                    Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);

                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                } else {
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.').json_encode($model_registro_pago_matricula->getErrors()),
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

    public function actionExportpdf() { // accion para descargar pdf de materias registradas
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
                    $modelPersona = Persona::findOne($registro_pago_matricula_model->per_id);
                    $matriculacion_model = new Matriculacion();
                    $modelRegOnl = RegistroOnline::find()->where(["per_id" => $registro_pago_matricula_model->per_id])->orderBy(['ron_id'=>SORT_DESC])->one();
                    $data_student = $matriculacion_model->getDataStudenbyRonId($modelRegOnl->ron_id);
                    $from = Yii::$app->params["adminEmail"];
                    $to = array(
                        "0" => $modelPersona->per_correo,
                    );
                    $asunto = Academico::t("matriculacion", "Online Registration");
                    if($state == 2){                         
                        $body = Utilities::getMailMessage("pagonegado", 
                            array("[[user]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido, 
                                  "[[periodo]]" => $data_student["pla_periodo_academico"], 
                                  "[[modalidad]]" => $data_student["mod_nombre"]), 
                            Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                        $titulo_mensaje = Academico::t("matriculacion", "Online Enrollment Registration Confirmation");

                        Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body);
                    } else {                        
                        $body = Utilities::getMailMessage("generado", 
                            array("[[user]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido, 
                                  "[[periodo]]" => $data_student["pla_periodo_academico"], 
                                  "[[modalidad]]" => $data_student["mod_nombre"]), 
                            Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                        $titulo_mensaje = Academico::t("matriculacion", "Online Enrollment Registration Confirmation");

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
            return $this->redirect('index');
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
