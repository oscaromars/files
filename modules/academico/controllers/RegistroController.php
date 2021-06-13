<?php

namespace app\modules\academico\controllers;

use app\models\Canton;
use app\models\EmpresaPersona;
use Yii;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\models\ExportFile;
use app\models\NivelInstruccion;
use app\models\Pais;
use app\models\Usuario;
use app\models\Persona;
use app\models\Provincia;
use app\models\UsuaGrolEper;
use app\modules\academico\models\CancelacionRegistroOnline;
use app\modules\academico\models\CancelacionRegistroOnlineItem;
use app\modules\academico\models\EnrolamientoAgreement;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\EstudiantePagoCarrera;
use app\modules\academico\models\FechaGraduacion;
use app\modules\academico\models\Matriculacion;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\Planificacion;
use app\modules\academico\models\PlanificacionEstudiante;
use app\modules\academico\models\ProgramaCostoCredito;
use app\modules\academico\models\RegistroAdicionalMaterias;
use app\modules\academico\models\RegistroConfiguracion;
use app\modules\academico\models\RegistroOnline;
use app\modules\academico\models\RegistroOnlineCuota;
use app\modules\academico\models\RegistroOnlineItem;
use app\modules\academico\models\RegistroPagoMatricula;
use app\modules\financiero\models\DatosPagoRegistro;
use app\modules\financiero\models\PagosFacturaEstudiante;
use app\modules\financiero\models\FacturasPendientesEstudiante;
use app\modules\academico\models\UnidadAcademica;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\modules\academico\Module as Academico;
use app\modules\financiero\models\FormaPago;
use app\modules\financiero\models\Secuencias;
use Faker\Provider\File;

Academico::registerTranslations();


class RegistroController extends \app\components\CController {
    public $pdf_cla_acceso = "";
     public function init() {
        if (!is_dir(Yii::getAlias('@bower')))
            Yii::setAlias('@bower', '@vendor/bower-asset');
        return parent::init();
    }

    public $arrCredito = [
        '1' => 'Total Payment of Program', 
        '2' => 'Total Payment of Period',
        '3' => 'Direct Credit', 
    ];

    public $arrCreditoNew = [
        '2' => 'Total Payment of Period',
        '3' => 'Direct Credit', 
    ];

    private function getCreditoPay(){
        $arrCredito = $this->arrCredito;
        for($i = 1; $i <= count($arrCredito); $i++){
            $arrCredito[$i] = Academico::t('matriculacion', $arrCredito[$i]);
        }
        return $arrCredito;
    }

    private function getCreditoPayNew(){
        $arrCredito = $this->arrCreditoNew;
        for($i = 2; $i <= count($arrCredito); $i++){
            $arrCredito[$i] = Academico::t('matriculacion', $arrCredito[$i]);
        }
        return $arrCredito;
    }

    //public $nombreUniversidad = "Miami Business Technological University";
    //public $telefonoUniversidad = "(305) 984-2003 / (305) 984-2294";
    //public $direccionUniversidad = "7791 NW 46th ST. STE 407. Miami, Florida 33166-5485";

    public function actionIndex($per_id=null,$costoc=null){
        $perid = Yii::$app->session->get("PB_perid"); 
        if ($per_id==Null) { $per_id = Yii::$app->session->get("PB_perid"); } 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $data = Yii::$app->request->get();
        $model = new RegistroPagoMatricula();
        $resp_perfil = new RegistroPagoMatricula();
        
        $emp_perMod = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $per_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
        $grol_id = 37; //grol_id = 32 es estudiante
        $usagrolMod = NULL;
        $esEstu = FALSE;
        if($emp_perMod){
            $usagrolMod = UsuaGrolEper::findOne(['eper_id' => $emp_perMod->eper_id, 'usu_id' => $usu_id, 'grol_id' => '37', 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
        }
        if($usagrolMod) $esEstu = TRUE;
        if ($per_id != $perid) { $esEstu = TRUE; } 


\app\models\Utilities::putMessageLogFile('perid: '.$perid);
\app\models\Utilities::putMessageLogFile('per_id: '.$per_id);

//\app\models\Utilities::putMessageLogFile('usagrolMod: '.$usagrolMod);
\app\models\Utilities::putMessageLogFile('usu_id:     '.$usu_id);


        $resp_grupo_id = $resp_perfil->getPerfilSearchListPago($usu_id);
        $grupo_id = $resp_grupo_id ['gru_id'];


        Yii::$app->session->set('usugrolMod', $usugrolMod);
        Yii::$app->session->set('per_id_perid', $per_id.'-'.$perid);

\app\models\Utilities::putMessageLogFile('FUERA del PBgetFilter $esEstu: '.$esEstu);

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
    \app\models\Utilities::putMessageLogFile('FUERA AJAX ');
            if (isset($data["mod_id"])) {
   \app\models\Utilities::putMessageLogFile('DENTRO AJAX ');
                $arr_pla_per = Planificacion::getPeriodosAcademicoPorModalidad($data["mod_id"]);
                $message = array("arr_pla_per" => $arr_pla_per);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }


        if ($data['PBgetFilter']) {
            $search = $data['search'];
            $periodo = $data['periodo'];
            $modalidad = $data['mod_id'];
            $estado = $data['estado'];
\app\models\Utilities::putMessageLogFile('DENTRO del PBgetFilter $modalidad: '.$modalidad);
\app\models\Utilities::putMessageLogFile('DENTRO del PBgetFilter $periodo: '.$periodo);

            return $this->renderPartial('index-grid', [
                'model' => $model->getAllListRegistryPaymentGrid($search, $esEstu, $modalidad, $estado, $periodo, true, $per_id, $grupo_id ),
            ]);
        }

        $arr_status = [
            -1 => Academico::t("matriculacion", "-- Select Status --"), 
            0 => Academico::t("registro", "To Pay"), 
            1 => Academico::t("registro", "To Check"), 
            2 => Academico::t("registro", "Paid Out"),
        ];
        //$arr_pla_per = Planificacion::getPeriodosAcademicoPorModalidad();

        /*$arr_modalidad = Planificacion::find()
                ->select(['m.mod_id', 'm.mod_nombre'])
                ->join('inner join', 'modalidad m')
                ->where('pla_estado_logico = 1 and pla_estado = 1 and m.mod_estado =1 and m.mod_estado_logico = 1')
                ->asArray()
                ->all();*/
        //$registro_pago_matricula = new RegistroPagoMatricula();
        //$modalidad = $registro_pago_matricula->getModalidadEstudiante($per_id);

        $resp_mod_pago_matricula = new Modalidad();
        $modalidad = $resp_mod_pago_matricula->getModalidadEstudiantePM($per_id);

        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();             
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $modalidadT = $mod_modalidad->consultarModalidad($arr_unidad[1]["id"], 1);

        \app\models\Utilities::putMessageLogFile('grupo_id:     '.$grupo_id);

        if ( $grupo_id == 12){
            \app\models\Utilities::putMessageLogFile('modalidad:     '.$modalidad[0]['id']);
           $arr_pla_per = Planificacion::getPeriodosAcademicoPorModalidad($modalidad[0]['id']);
        }else{
            \app\models\Utilities::putMessageLogFile('modalidadT:     '.$$modalidadT[0]["id"]);
            $arr_pla_per = Planificacion::getPeriodosAcademicoPorModalidad($modalidadT[0]["id"]);
        }

        return $this->render('index', [
            'esEstu' => TRUE,
            'grupo_id' => $grupo_id,            
            'per_id' => $per_id,
            //'modalidad'=>array_merge([1 => Academico::t("matriculacion", $modalidad['name'])]),//$modalidad['name']
            'modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $modalidad), "id", "name"),
            'modalidadT' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $modalidadT), "id", "name"),
            'periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_pla_per), "id", "name"),
            //'periodoAcademico' => array_merge([0 => Academico::t("matriculacion", "-- Select Academic Period --")], ArrayHelper::map($arr_pla_per, "pla_id", "pla_periodo_academico")),
            'arr_status' => $arr_status,
              'costo' => $costoc,
            'model' => $model->getAllListRegistryPaymentGrid(NULL, TRUE/*$esEstu*/, NULL, NULL, NULL, true, $per_id, $grupo_id ),
            

        ]);
    }

    public function actionNew($id,$ron, $cuotas,$idtotal){
        \app\models\Utilities::putMessageLogFile('RegistroOnline::findOne($id);: '.$id);
        $model = RegistroOnline::findOne(base64_decode($id));
        $data = Yii::$app->request->get();
        $rama_id = base64_decode($data['ron']);
        $cuotas = base64_decode($data['cuotas']);
        $idtotal = base64_decode($data['idtotal']);
        $costo = $data['costo'];
        $registroOnline = new RegistroOnline();
        $pes_id = $registroOnline->getPes_id($id);//$model[0]['pes_id'];
        $pes_id = $pes_id[0]['id'];
        $ron_id = $registroOnline->getRonId($id);

        $_SESSION['JSLANG']['JAN'] = Academico::t('registro', 'JAN');
        $_SESSION['JSLANG']['FEB'] = Academico::t('registro', 'FEB');
        $_SESSION['JSLANG']['MAR'] = Academico::t('registro', 'MAR');
        $_SESSION['JSLANG']['APR'] = Academico::t('registro', 'APR');
        $_SESSION['JSLANG']['MAY'] = Academico::t('registro', 'MAY');
        $_SESSION['JSLANG']['JUN'] = Academico::t('registro', 'JUN');
        $_SESSION['JSLANG']['JUL'] = Academico::t('registro', 'JUL');
        $_SESSION['JSLANG']['AUG'] = Academico::t('registro', 'AUG');
        $_SESSION['JSLANG']['SEP'] = Academico::t('registro', 'SEP');
        $_SESSION['JSLANG']['OCT'] = Academico::t('registro', 'OCT');
        $_SESSION['JSLANG']['NOV'] = Academico::t('registro', 'NOV');
        $_SESSION['JSLANG']['DEC'] = Academico::t('registro', 'DEC');
        $_SESSION['JSLANG']['The value of the first payment must be greater than or equal to'] = Academico::t('registro', 'The value of the first payment must be greater than or equal to');
        $_SESSION['JSLANG']['The value of the first payment must be less than or equal to'] = Academico::t('registro', 'The value of the first payment must be less than or equal to');
        $_SESSION['JSLANG']['To Continue you must accept terms and conditions'] = Academico::t('registro', 'To Continue you must accept terms and conditions');
        $_SESSION['JSLANG']['Please attach a file in format png or jpeg'] = Academico::t('registro', 'Please attach a file in format png or jpeg');
        $_SESSION['JSLANG']['Please select a number of installments.'] = Academico::t('registro', 'Please select a number of installments.');

        $model_pes = PlanificacionEstudiante::findOne($pes_id);
        $per_id = base64_decode($id);
        $model_pes = PlanificacionEstudiante::findOne($per_id);
        $planificacionEst = new PlanificacionEstudiante();
        $pla = $planificacionEst->getPla_id($per_id);
        $pla_id = $pla[0]['id'];
        $model_pla = Planificacion::findOne($pla_id);
        /*$planificacion = new Planificacion();
        $paca_id = $planificacion->getPaca_id($per_id);
        $paca_id = $paca_id[0]['id'];*/
        
        
        $emp_id = Yii::$app->session->get("PB_idempresa");
        \app\models\Utilities::putMessageLogFile(' Periodo y emp_id: '.$per_id . " ".$emp_id  );
        $arrCarrera = RegistroPagoMatricula::getCarreraByPersona($per_id, $emp_id);
        $modelFP = new FormaPago();
        $arr_forma_pago = $modelFP->consultarFormaPago();
        unset($arr_forma_pago[1]);
        unset($arr_forma_pago[2]);
        unset($arr_forma_pago[5]);
        unset($arr_forma_pago[6]);
        unset($arr_forma_pago[7]);
        unset($arr_forma_pago[8]);
        // get Credits and get Cost x Credit
        $eaca_id = $arrCarrera['eaca_id'];
        $mod_id = $arrCarrera['mod_id'];
        $arr_cost_mat = RegistroOnlineItem::getTotalCreditsByRegister($id, $rama_id);
        //$modelProCre = ProgramaCostoCredito::findOne(['eaca_id' => $eaca_id, 'mod_id' => $mod_id, 'pccr_estado' => '1', 'pccr_estado_logico' => '1']);
        $programaCostoCredito = new ProgramaCostoCredito();
        $modelProCre = $programaCostoCredito->getValores($eaca_id,$mod_id);
        $costCarrera = $modelProCre[0]['pccr_costo_carrera'];
        $costoCredito = $modelProCre[0]['pccr_costo_credito'];
        $creditosCarrera = $modelProCre[0]['pccr_creditos'];
        $arr_forma_pago = ArrayHelper::map($arr_forma_pago, "id", "value");
        //$model_paca = PeriodoAcademico::findOne(['paca_id' => $paca_id, 'paca_estado' => '1', 'paca_estado_logico' => '1',]);
        $mod_periodo = new PlanificacionEstudiante();
        $periodo = $mod_periodo->consultaPeriodoAcadVigenteFechas();
        $arr_vence = RegistroPagoMatricula::getFechasVencimiento2($periodo[0]['fecha_fin']);
        $registro_pago_matricula = new RegistroPagoMatricula();
        $arr_vencimiento = $registro_pago_matricula->fechasVencimientoPeriodo($pla_id);
        //$cuotas = $registro_pago_matricula->getCuotasPeriodo($rama_id);
        $dataValue = array();//['Id' => 1, 'Pago' => 'Payment #1', 'Porcentaje' => '100.00%', 'Valor' => '$'.(number_format($arr_cost_mat['Costo'], 2, '.', ','))];
        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $dataValue,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Pago"],
            ],
        ]);
        $estudiante = $pla[0]['nombres'];
        //$cuotas = ['0' => Academico::t('registro', '-- Select a number of installments --'), /*'2' => '2', '3' => '3', '4' => '4',*/ '5' => '5', '6' => '6'];
        //$cuotas = ['6' => '6'];
        return $this->render('new', [
            'model' => $model,
            'estudiante' => $estudiante,//$model_pes->pes_nombres,
            'carrera' => $arrCarrera['Carrera'],
            'periodo' => $periodo[0]['name'],
            'arr_forma_pago' => $arr_forma_pago,
            'arr_credito' => $this->getCreditoPayNew(),
            'id' => $id,
            'cuotas' => $cuotas?$cuotas:6,
            'dataGrid' => $dataProvider,
            'costCarrera' => $costCarrera,//,(number_format($costCarrera, 2, '.', ',')),
            'costoCredito' => $modelProCre[0]['cosotzero'],
            'creditosCarrera' => $$modelProCre[0]['costozero'],
            'creditoItem' => $arr_cost_mat['Credito'],
            'costoItem' => $costCarrera,//(number_format($arr_cost_mat['Costo'], 2, '.', ',')),
            'botonPagos' => Yii::$app->params["botonPagos"],
            'arr_vencimiento' => $arr_vencimiento,//$arr_vence,
            'arr_ve' => $arr_vencimiento,
            'rama_id' => $rama_id,
            'model_pes' => $modelProCre[0]['pccr_costo_carrera'],
            'pes_id' => $pes_id,
            'ron_id' => $ron_id[0]['id'],
            'costo' => $idtotal,
            'rama' => $rama_id,
            'pla_id' => $pla_id,
        ]);
    }

    public function actionEdit($id){
        $model = RegistroOnline::findOne($id);
        $data = Yii::$app->request->get();
        $rama_id = $data['rama_id'];
        $modelRegAd = RegistroAdicionalMaterias::findOne(['rama_id' => $rama_id, 'rama_estado' => '1', 'rama_estado_logico' => '1',]);

        $_SESSION['JSLANG']['JAN'] = Academico::t('registro', 'JAN');
        $_SESSION['JSLANG']['FEB'] = Academico::t('registro', 'FEB');
        $_SESSION['JSLANG']['MAR'] = Academico::t('registro', 'MAR');
        $_SESSION['JSLANG']['APR'] = Academico::t('registro', 'APR');
        $_SESSION['JSLANG']['MAY'] = Academico::t('registro', 'MAY');
        $_SESSION['JSLANG']['JUN'] = Academico::t('registro', 'JUN');
        $_SESSION['JSLANG']['JUL'] = Academico::t('registro', 'JUL');
        $_SESSION['JSLANG']['AUG'] = Academico::t('registro', 'AUG');
        $_SESSION['JSLANG']['SEP'] = Academico::t('registro', 'SEP');
        $_SESSION['JSLANG']['OCT'] = Academico::t('registro', 'OCT');
        $_SESSION['JSLANG']['NOV'] = Academico::t('registro', 'NOV');
        $_SESSION['JSLANG']['DEC'] = Academico::t('registro', 'DEC');
        $_SESSION['JSLANG']['The value of the first payment must be greater than or equal to'] = Academico::t('registro', 'The value of the first payment must be greater than or equal to');
        $_SESSION['JSLANG']['The value of the first payment must be less than or equal to'] = Academico::t('registro', 'The value of the first payment must be less than or equal to');
        $_SESSION['JSLANG']['To Continue you must accept terms and conditions'] = Academico::t('registro', 'To Continue you must accept terms and conditions');
        $_SESSION['JSLANG']['Please attach a file in format png or jpeg'] = Academico::t('registro', 'Please attach a file in format png or jpeg');

        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        
        $emp_perMod = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $per_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
        $grol_id = 32; //grol_id = 32 es estudiante
        $usagrolMod = NULL;
        $esEstu = FALSE;
        if($emp_perMod){
            $usagrolMod = UsuaGrolEper::findOne(['eper_id' => $emp_perMod->eper_id, 'usu_id' => $usu_id, 'grol_id' => $grol_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
        }
        if($usagrolMod) $esEstu = TRUE;

        $model_pes = PlanificacionEstudiante::findOne($model->pes_id);
        $model_pla = Planificacion::findOne($model_pes->pla_id);
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $arrCarrera = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id);
        $modelFP = new FormaPago();
        $arr_forma_pago = $modelFP->consultarFormaPago();
        unset($arr_forma_pago[0]);
        unset($arr_forma_pago[1]);
        unset($arr_forma_pago[2]);
        unset($arr_forma_pago[6]);
        unset($arr_forma_pago[7]);
        unset($arr_forma_pago[8]);
        // get Credits and get Cost x Credit
        $eaca_id = $arrCarrera['eaca_id'];
        $mod_id = $arrCarrera['mod_id'];
        $uaca_id = $arrCarrera['uaca_id'];
        $arr_cost_mat = RegistroOnlineItem::getTotalCreditsByRegister($id, $rama_id);
        $modelProCre = ProgramaCostoCredito::findOne(['eaca_id' => $eaca_id, 'mod_id' => $mod_id, 'pccr_estado' => '1', 'pccr_estado_logico' => '1']);
        $costCarrera = $modelProCre->pccr_costo_carrera;
        $costoCredito = $modelProCre->pccr_costo_credito;
        $creditosCarrera = $modelProCre->pccr_creditos;
        $arr_forma_pago = ArrayHelper::map($arr_forma_pago, "id", "value");
        $per_id = ($esEstu)?($per_id):($model->per_id); 
        $model_ron = RegistroOnline::findOne(['ron_id' => $id, 'per_id' => $per_id, 'ron_estado' => '1', 'ron_estado_logico' => '1']);
        $model_pes = PlanificacionEstudiante::findOne(['pes_id' => $model_ron->pes_id, 'per_id' => $per_id, 'pes_estado' => '1', 'pes_estado_logico' => '1']);
        $model_rpm = RegistroPagoMatricula::findOne(['per_id' => $per_id, 'pla_id' => $model_pes->pla_id, 'ron_id' => $id, 'rpm_estado' => '1', 'rpm_estado_logico' => '1']);
        
        if(!$model_rpm){
            return $this->redirect('index');
        }
        $value_total = number_format($model_rpm->rpm_total, 2, '.', ',');
        $model_roc = RegistroOnlineCuota::findAll(['rpm_id' => $modelRegAd->rpm_id, 'ron_id' => $id, 'roc_estado' => '1', 'roc_estado_logico' => '1']);
        $dataValue = array();
        $con = 0;
        $model_can = CancelacionRegistroOnline::findOne(['ron_id' => $id, 'cron_estado' => '1', 'cron_estado_logico' => '1']);
        if($model_can){
            $modelEnroll = EnrolamientoAgreement::findOne(['ron_id' => $id, 'rpm_id' => $modelRegAd->rpm_id, 'per_id' => $per_id, 'eaca_id' => $eaca_id, 'uaca_id' => $uaca_id, 'eagr_estado' => '1', 'eagr_estado_logico' => '1',]);
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

        $dataProvider = new ArrayDataProvider([
            'key' => 'Id',
            'allModels' => $dataValue,
            'pagination' => [
                'pageSize' => Yii::$app->params["pageSize"],
            ],
            'sort' => [
                'attributes' => ["Pago"],
            ],
        ]);
        
        return $this->render('edit', [
            'model' => $model,
            'estudiante' => $model_pes->pes_nombres,
            'carrera' => $arrCarrera['Carrera'],
            'periodo' => $model_pla->pla_periodo_academico,
            'arr_forma_pago' => $arr_forma_pago,
            'arr_credito' => $this->getCreditoPay(),
            'id' => $id,
            'cuotas' => ['0' => Academico::t('registro', '-- Select a number of installments --'), '2' => '2', '3' => '3', '4' => '4', '5' => '5',],
            'dataGrid' => $dataProvider,
            'costCarrera' => (number_format($costCarrera, 2, '.', ',')),
            'costoCredito' => $costoCredito,
            'creditosCarrera' => $creditosCarrera,
            'creditoItem' => $arr_cost_mat['Credito'],
            'costoItem' => (number_format($arr_cost_mat['Costo'], 2, '.', ',')),
            'value_credit' => $model_rpm->rpm_tipo_pago,
            'value_payment' => $model_rpm->fpag_id,
            'lnk_download' => $model_rpm->rpm_archivo,
            'value_total' => $value_total,
            'value_interes' => number_format($model_rpm->rpm_interes, 2, '.', ','),
            'value_financiamiento' => number_format($model_rpm->rpm_financiamiento, 2, '.', ','),
            'value_cuotas' => $con,
            'value_pago_inicial' => $dataValue[0]['Valor'],
            'value_terminos' => $model_rpm->rpm_acepta_terminos,
            'rpm_id' => $model_rpm->rpm_id,
            'rama_id' => $rama_id,
        ]);
    }


    //Pago de registro de matricula 
    public function actionSave(){//$id){   
                //$id = base64_decode($id);  
                $per_id = Yii::$app->session->get("PB_perid"); 
                $usu_id = Yii::$app->session->get("PB_iduser");
                $emp_id = Yii::$app->session->get("PB_idempresa");
                $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
                if (Yii::$app->request->isAjax) {
                    $data = Yii::$app->request->post(); 
                    $id = base64_decode($data['per_id']);
                    \app\models\Utilities::putMessageLogFile('ID...: '.$id);  
                    $model = RegistroOnline::findOne($id);
           
                    if ($data["upload_file"]) {
                        if (empty($_FILES)) {
                            return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
                        }
                        //Recibe Parámetros
                        $files = $_FILES[key($_FILES)];
                        $arrIm = explode(".", basename($files['name']));
                        $namefile= $data["name_file"];
                        $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                        if ($typeFile == 'jpeg' || $typeFile == 'png' || $typeFile == 'jpg' || $typeFile == 'jpeg') {
                            $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosmatricula/" . $namefile . "." . $typeFile;
                            $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                            if ($status) {
                                return true;
                            } else {
                                return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) .". Try again.")]);
                            }
                        } else {
                            return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Only format Images jpg, jpeg, png. Try again.")]);
                        }
                    }
                    $per_id = $data['per_id'];
                    $con = Yii::$app->db_facturacion->beginTransaction();
                    $transaction = Yii::$app->db_academico->beginTransaction();
                    $error = false;
                    try{
                        $archivo    = $data["archivo"];
                        $rama_id    = $data['rama_id'];
                        $tpago      = $data['tpago'];
                        $fpago      = $data['fpago'];  
                        // consultar el valor de costo de graduacion, para agregarlo si paga toda la carrera
                        $arrCarreras = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id);
                        $eaca_ids = $arrCarreras['eaca_id'];
                        $mod_ids = $arrCarreras['mod_id'];
                        $est_id =  $arrCarreras['est_id'];
                        $modelProGra = ProgramaCostoCredito::findOne(['eaca_id' => $eaca_ids, 'mod_id' => $mod_ids, 'pccr_estado' => '1', 'pccr_estado_logico' => '1']);
                        $costGraduacion = $modelProGra->pccr_costo_graduacion;                     
                        switch ($tpago) {
                            case 1:
                                // Se debe obtener de la tabla db_academico_mbtu.programa_costo_cretido, columna costo_graduacion $200
                                $totaldesc = str_replace(',','',$data['total']) - (str_replace(',','',$data['total']) * Yii::$app->params["descarrera"]) + $costGraduacion;
                                break;
                            case 2:
                                $totaldesc = str_replace(',','',$data['total']) - (str_replace(',','',$data['total']) * Yii::$app->params["desperiodo"]);
                                break;                            
                            default:
                            $totaldesc = str_replace(',','',$data['total']);
                        }                        
                        \app\models\Utilities::putMessageLogFile('Id de estudiante  Didimo: '. $est_id);
                        /*\app\models\Utilities::putMessageLogFile('descarrera : '.Yii::$app->params["descarrera"]);
                        \app\models\Utilities::putMessageLogFile('desperiodo : '.Yii::$app->params["desperiodo"]);
                        \app\models\Utilities::putMessageLogFile('tpago : '.$tpago);
                        \app\models\Utilities::putMessageLogFile('totalsindesc : '.$data['total']);
                        \app\models\Utilities::putMessageLogFile('totaldesc : '.$totaldesc);*/
                        $total      = $data['total'];
                        $interes    = $data['interes'];
                        $financiamiento = $data['financiamiento'];
                        $numcuotas  = $data['numcuotas'];
                        $cuotaini   = $data['cuotaini'];
                        $arr_cuotas = $data['valcuotas'];
                        $termino    = $data['termino'];
                        $model_ron  = RegistroOnline::findOne(['ron_id' => $id, 'per_id' => $per_id, 'ron_estado' => '1', 'ron_estado_logico' => '1']);
                        $model_pes = PlanificacionEstudiante::findOne(['pes_id' => $model_ron->pes_id, 'per_id' => $per_id, 'pes_estado' => '1', 'pes_estado_logico' => '1']);
                        $model_pla = Planificacion::findOne(['pla_id' => $model_pes->pla_id, 'pla_estado' => '1', 'pla_estado_logico' => '1',]);
                        $model_paca = PeriodoAcademico::findOne(['paca_id' => $model_pla->paca_id, 'paca_estado' => '1', 'paca_estado_logico' => '1',]);
                        $periodoAca = $model_pla->pla_periodo_academico;
                        /*
                        //DBE
                        */
                       // \app\models\Utilities::putMessageLogFile('Action Save:($id);: '.$id);
                        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>CUOTAS>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                        /*$per_id = @Yii::$app->session->get("PB_perid");
                        $usuario = @Yii::$app->user->identity->usu_id;  
                        if (Yii::$app->request->isAjax) {
                            $data = Yii::$app->request->post();*/
                          
                            //return;
//                        }/**/
                        //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>CUOTAS>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                       // \app\models\Utilities::putMessageLogFile('actionModificarcargacartera fin');
                        

                        /*
                        $model_rpm = RegistroPagoMatricula::findOne(['per_id' => $per_id, 'pla_id' => $model_pes->pla_id, 'ron_id' => $id, 'rpm_estado' => '1', 'rpm_estado_logico' => '1']);
                        if($model_rpm){
                            throw new Exception('Error Registry already exists.');
                        }*/

                        //$model_ron->ron_valor_matricula = str_replace(',','',$total);
                       // $model_ron->ron_valor_matricula = $totaldesc?$totaldesc:0;
                        //$model_ron->ron_valor_gastos_adm = str_replace(',','',$interes);
                        //$model_ron->ron_valor_arancel = str_replace(',','',$financiamiento); 
                       /* if(!$model_ron->save()){
                            throw new Exception('Error to Update Online Registry.');
                        }*/

                        // Yii::$app->params["descarrera"]
                        // Yii::$app->params["desperiodo"]
                        $modelRegPm = new RegistroPagoMatricula();
                        $modelReg = new RegistroPagoMatricula();
                        $modelReg->per_id = $per_id;
                        $modelReg->pla_id = $model_pes->pla_id;
                        $modelReg->ron_id = $id;
                        $modelReg->fpag_id = $fpago; // aqui tengo que enviar forma de pago
                        $modelReg->rpm_archivo =  "pagosmatricula/" . $archivo; // si es forma de pago stripe no carga archivo
                        $modelReg->rpm_estado_aprobacion = "0"; /** 0->No revisado, 1->Aprobado, 2->Rechazado */
                        $modelReg->rpm_estado_generado = "1"; /**0->por pagar, 1->por revisar, 2->pagado */
                        $modelReg->rpm_acepta_terminos = $termino;
                        $modelReg->rpm_estado = "1";
                        $modelReg->rpm_estado_logico = "1";
                        $modelReg->rpm_observacion = "";
                        $modelReg->rpm_tipo_pago = $tpago;
                        //$modelReg->rpm_total = str_replace(',','',$total);
                        $modelReg->rpm_total = $totaldesc;
                        $modelReg->rpm_interes = str_replace(',','',$interes);
                        $modelReg->rpm_financiamiento = str_replace(',','',$financiamiento);
                        if($fpago == 6  || $fpago==1){
                            $modelReg->rpm_estado_aprobacion = "1";
                            //$modelReg->rpm_usuario_apruebareprueba = "1";
                        }
                         else{
                            $modelReg->rpm_estado_aprobacion = "0";
                         }
                        
                       
                        $arr_cuotas_ro = array();
                        $arr_vencimiento = array();
                        $sumCuota = 0;


                        $fechaFinReg = $model_paca->paca_fecha_inicio;
                        $fechaFinPay = date('Y-m-d', strtotime("$fechaFinReg +5 day"));
                        $initialMonth = date('F', strtotime("$fechaFinReg +5 day"));
                        $initialMonNum = date('m', strtotime("$fechaFinReg +5 day"));
                        $initialDay = date('d', strtotime("$fechaFinReg +5 day"));
                        $initialYear = date('y', strtotime("$fechaFinReg +5 day"));

                        if ($modelReg->save()) {   
                        //Registrar datos de factura pago stripe
                                    if($fpago == 6  || $fpago==1){
                                            //Inserta datos de factura pago
                                        $datosPagoRegistro = new  DatosPagoRegistro();
                                        \app\models\Utilities::putMessageLogFile('dpre_ssn_id :'.$data['factssnid']);
                                        \app\models\Utilities::putMessageLogFile('dpre_nombres :'.$data['factnombre']);
                                        \app\models\Utilities::putMessageLogFile('dpre_apellidos :'.$data['factapellido']);
                                        \app\models\Utilities::putMessageLogFile('dpre_correo :'.$data['factcorreo']);
                                        \app\models\Utilities::putMessageLogFile('dpre_direccion :'.$data['factdirecc']);
                                        \app\models\Utilities::putMessageLogFile('dpre_telefono :'.$data['facttelef']);
                                        \app\models\Utilities::putMessageLogFile('$modelReg->rpm_id, :'.$modelReg->rpm_id);
                                        $pfes_id = "";
                                        $resdatosFact = $datosPagoRegistro->insertarData(
                                            $fpago, 
                                            $pfes_id, 
                                            $data['factssnid'], 
                                            $data['factnombre'], 
                                            $data['factapellido'], 
                                            $data['factcorreo'], 
                                            $data['factdirecc'], 
                                            $data['facttelef'], 
                                            $per_id,
                                            $modelReg->rpm_id
                                        );
                                    }

                                    $modelRegPaAd = RegistroAdicionalMaterias::findOne(['rama_id' => $rama_id, 'ron_id' => $id, 'per_id' => $per_id, 'rama_estado' => '1', 'rama_estado_logico' => '1']);
                                    $modelRegPaAd->rpm_id = $modelReg->rpm_id;
                                    if(!$modelRegPaAd->save()){
                                        throw new Exception('Error to Update Aditional Subjects Registry.');
                                    }
                                    //para guardar los pendientes de pago del estudiante 
                                    $mod_facturas_pendientes_est = new FacturasPendientesEstudiante();
                                    $pagosFacturaEstudiante= new PagosFacturaEstudiante();
                                    $modelRpm = $modelRegPm->getRegistroPagoMatriculaCuotas($id,$per_id);
                                    $modelCuotaNew = new RegistroOnlineCuota();
                                    // se crea las cuotas 
                                    if($tpago == 1 || $tpago == 2){
                                        //totaldesc
                                        //$sumCuota = str_replace([',', '$'],'',$total);
                                        $sumCuota = $totaldesc;
                                        $modelCuota = new RegistroOnlineCuota();
                                        $modelCuota->ron_id = $id;
                                        $modelCuota->rpm_id = $modelReg->rpm_id;
                                        $modelCuota->roc_num_cuota = "1";
                                        $modelCuota->roc_porcentaje = '100.00%';
                                        //$modelCuota->roc_costo = str_replace([',', '$'],'',$total);
                                        $modelCuota->roc_costo = $totaldesc;
                                        $modelCuota->roc_estado = '1';
                                        $modelCuota->roc_estado_logico = '1';
                                        //$arr_cuotas_ro[] = str_replace([',', '$'],'',$total);
                                        $arr_cuotas_ro[] = $totaldesc;
                                        $modelCuota->roc_estado_pago = "C";
                                        $modelCuota->fpe_id = 0;
                                        $modelCuota->roc_vencimiento = strtoupper(Academico::t('matriculacion', date('F'))) . " " . date('d') . "/" . date('y');
                                        $arr_vencimiento[] = $modelCuota->roc_vencimiento;
                                        if(!$modelCuota->save()){
                                            throw new Exception('Error to Save Payment Registry. '.json_encode($modelCuota->getErrors()));
                                        }
                                    }else{


                                        //si es Tipo de pago a credito verificar que si ya tiene un credito por el periodo actual.
                                        // se debe sumar el valor de las cuota nuevas a las cuotas ya existenetes  sin pasar del mes Agosto.
                                       
                                        $pfes_banco = 0;
                                      
                                        //si no existe un pago adicional de registro para el periodo actual del una persona.

                                        $arr_vence = RegistroPagoMatricula::getFechasVencimiento($model_paca->paca_fecha_inicio);


                                         //Registrar lo pendiente para pago de mensualidades  
                                         $fpe_tipo_documento = "001";  //mensualidades                                    

                                            //Si ya tiene un pago de matricula previo y con tipo de pago 3  credito directo  sumar valor de cuota a saldos pendientes.


                                            $RegistroPagoMatricula = new RegistroPagoMatricula();

                                              //Recorrer el numero de cuotas seleccionadas excepto la primera que es de pago  al instante
                                             Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><log 1.1 : Nuemro de cuotas'. $numcuotas);
                                            for($i=0; $i < $numcuotas; $i++){

                                                //empiezo desde la cuota numero dos para credito

                                                    
                                                    $val_new = $arr_cuotas['data'][$i][3];
                                                    //$sumCuota += str_replace([',', '$'],'',$val_new);
                                                    $per = $arr_cuotas['data'][$i][2];
                                                    $numC = $i + 1;
                                                     $fpe_vence = $arr_vence[$i];
                                                       //Verifico el numero de cuota  tambien tienen pendientes aun para sumar  valor a pendiente 
                                                    $TieneRpM = $RegistroPagoMatricula->getFacturasPendientesCuotasBycuota($numC,$per_id);
                                                     Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><log 1.2  Tiene factura pendiente de cuota: '.$numC. ' fpe: '.$TieneRpM['fpe_id']);

                                                    if($TieneRpM['fpe_id']>0){
                                                         Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><log 1.3 : Actualiza saldos Facturas pendientes');
                                                        //este numero de cuota esta pendiente sumamos el valor actual de cuota
                                                        $fpe_id = $TieneRpM['fpe_id'];
                                                        $updatePend =  $mod_facturas_pendientes_est->updateSaldosCuotasNewRegistroOnLine($fpe_id,str_replace([',', '$'],'',$val_new),$usu_id);
                                                         if($updatePend<=0){
                                                            throw new Exception('Error al actualizar saldo pendiente cuota');
                                                         }

                                                    }else{
                                                         //Caso  contrario se debe de crear un nuevo cuota pendiente 
                                                        
                                                        if($numC>1){   
                                                             Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><log 1.4 : Crea nuevo factura pendiente cuota');
                                                             $pagospendientesea = $mod_facturas_pendientes_est->crearCabeceraFacturaPendienteEstudiante($per_id,$numC,str_replace([',', '$'],'',$val_new),'001',$usu_id,$fpe_vence); 
                                                            \app\models\Utilities::putMessageLogFile('fpe_id: '.$pagospendientesea);     
                                                            if(!$pagospendientesea){
                                                                throw new Exception('Error to Save Payment Registry.');
                                                            }
                                                            $fpe_id = $pagospendientesea;

                                                        }
                                                       
                                                       
                                                    }
                                                   
                                                    //$val = $arr_cuotas['data'][$i][3];
                                                    //$sumCuota += str_replace([',', '$'],'',$val);
                                                     Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><log 1.5 : ARegistra en tablas de cuotas on linea');
                                                    $modelCuota = new RegistroOnlineCuota();
                                                    $modelCuota->ron_id = $id;
                                                    $modelCuota->rpm_id = $modelReg->rpm_id;
                                                    $modelCuota->roc_num_cuota = "$numC"."/".$numcuotas;
                                                    $modelCuota->roc_porcentaje = str_replace(',','',$per);
                                                    $modelCuota->roc_costo = str_replace([',', '$'],'',$val_new);
                                                    $modelCuota->roc_estado = '1';
                                                    $modelCuota->roc_estado_logico = '1';
                                                    if($numC>1)  
                                                        $modelCuota->fpe_id = $fpe_id;
                                                    else
                                                        $modelCuota->fpe_id = 0;

                                                    $modelCuota->roc_vencimiento = $arr_vence[$i];
                                                    //$modelCuota->roc_estado_pago = 'P';


                                                    if($numC==1){                                                          
                                                          if ($fpago==6 || $fpago==1){
                                                             $modelCuota->roc_estado_pago = "C";
                                                             $dpfa_estado_pago= 2;
                                                          }
                                                          else {
                                                             $dpfa_estado_pago= 1; 
                                                              $modelCuota->roc_estado_pago = "R";
                                                          } 
                                                            
                            
                                                    }else{ $modelCuota->roc_estado_pago = "P";}   

                                                    $arr_cuotas_ro[] = str_replace([',', '$'],'',$val);
                                                    //$modelCuota->roc_vencimiento = $arr_vence[$i];
                                                    $arr_vencimiento[] = $modelCuota->roc_vencimiento;

                                                    if(!$modelCuota->save()){
                                                        throw new Exception('Error to Save Payment Registry.');
                                                    }

                                                    //en caso de ser el primer pago 
                                                     if($numC==1){
                                                        Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><log 1.6 : Regiistro pago primera cuota ');
                                                        $pfes_referencia = "";
                                                        //$pfes_numero_documento = "";
                                                         $confac  = \Yii::$app->db_facturacion;
                                                        //Obtener secuencial
                                                         $pfes_numero_documento = Secuencias::nuevaSecuencia($confac, 2, 1, 1, 'REC');
                                                                         //insertarPagospendientes
                                                         $pfes_id_result  = $pagosFacturaEstudiante->crearPagosFacturaEstudiante($est_id,$pfes_referencia,$fpago,
                                                                            $pfes_numero_documento,$modelCuota->roc_costo,$pfes_observacion,
                                                                            $archivo,'C',$usu_id,$pfes_banco,''
                                                                        );
                                                        Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><Resultado de Id CAbecera de Pago '. $pfes_id_result);
                                                                            //detalle de pago de matricula inicial
                                                        $resp_detpagofactura = $pagosFacturaEstudiante->insertarDetpagospendientes($pfes_id_result,$modelCuota->roc_id,$modelCuota->roc_costo,$usu_id,$dpfa_estado_pago);

                                                      }
                                                    





                                            }   









//comentar 1

                                            $TieneRpM = $RegistroPagoMatricula->getFacturasPendientesCuotas($id,$per_id);
                                            $cantPendCuot = count($TieneRpM);
                                            \app\models\Utilities::putMessageLogFile(print_r($TieneRpM, true));
                                             if ($fpago==6  || $fpago==1){ 
                                                    $dpfa_estado_pago= 2;
                                                    $fpe_estado_pago = 'C';  
                                                 }else{ 
                                                    $dpfa_estado_pago= 1; 
                                                    $fpe_estado_pago = 'R';
                                                 }

                                            //Si hay facturas pendientes aun.
                                            if(count($TieneRpM)>0){


                                                //Registrar el primer cuota pagada con estda C
                                                 $numC =1;
                                                 $fpe_id = $TieneRpM[$j]['fpe_id'];
                                                 //$val_new = $arr_cuotas['data'][1][3];

                                                 $perFrts = $arr_cuotas['data'][1][2];
                                                 $valFrtsCuota = $arr_cuotas['data'][1][3];
                                                 
                                                 $sumCuota += str_replace([',', '$'],'',$valFrtsCuota);

                                                 $modelCuota1 = new RegistroOnlineCuota();
                                                 $modelCuota1->ron_id = $id;
                                                 $modelCuota1->rpm_id = $modelReg->rpm_id;
                                                 $modelCuota1->roc_num_cuota = "$numC"."/".$numcuotas;
                                                 $modelCuota1->roc_porcentaje = str_replace(',','',$perFrts);
                                                 $modelCuota1->roc_costo = str_replace([',', '$'],'',$valFrtsCuota);
                                                 $modelCuota1->roc_estado = '1';
                                                 $modelCuota1->roc_estado_logico = '1';
                                                 $modelCuota1->fpe_id = 0;
                                                 $modelCuota1->roc_vencimiento = $arr_vence[0];
                                                 $modelCuota1->roc_estado_pago = $fpe_estado_pago;
                                                 if(!$modelCuota1->save()){
                                                    throw new Exception('Error to Save Payment Registry Inicial.');
                                                 }
                                                 //registrar el primer pago en 
                                                
                                                 $pfes_referencia = "";
                                                //$pfes_numero_documento = "";
                                                 $confac  = \Yii::$app->db_facturacion;
                                                //Obtener secuencial
                                                 $pfes_numero_documento = Secuencias::nuevaSecuencia($confac, 2, 1, 1, 'REC');
                                                                 //insertarPagospendientes
                                                 $pfes_id_result  = $pagosFacturaEstudiante->crearPagosFacturaEstudiante($est_id,$pfes_referencia,$fpago,
                                                                    $pfes_numero_documento,  str_replace([',', '$'],'',$valFrtsCuota) ,$pfes_observacion,
                                                                    $archivo,'C',$usu_id,$pfes_banco,''
                                                                );
                                                Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><Resultado de Id CAbecera de Pago '. $pfes_id_result);
                                                                    //detalle de pago de matricula inicial
                                                $resp_detpagofactura = $pagosFacturaEstudiante->insertarDetpagospendientes($pfes_id_result,$modelCuota->roc_id,str_replace([',', '$'],'',$valFrtsCuota),$usu_id,$dpfa_estado_pago);


                                                 $Cuota2 = false;
                                                 $Cuota3 = false;
                                                 $Cuota4 = false;
                                                 $Cuota5 = false;



                                          







                                                for($j=0; $j < count($TieneRpM); $j++){
                                                     $num_cuota_upd = $TieneRpM[$j]['fpe_num_cuota'];
                                                     for($i=0; $i < $numcuotas; $i++){
                                                        $cuotaNum = $i+1;

                                                        if($num_cuota_upd == $cuotaNum ){

                                                            switch ($num_cuota_upd) {
                                                                case 2:
                                                                    $Cuota2 = true;
                                                                    break;
                                                                case 3:
                                                                    $Cuota3 = true;
                                                                    break;
                                                                case 4:
                                                                    $Cuota4 = true;
                                                                    break;
                                                                case 5:
                                                                    $Cuota5 = true;
                                                                    break;
                                                            }


                                                            $fpe_id = $TieneRpM[$j]['fpe_id'];
                                                            $val_new = $arr_cuotas['data'][$i][3];
                                                            $sumCuota += str_replace([',', '$'],'',$val_new);

                                                            \app\models\Utilities::putMessageLogFile('<<<<<<<<<Datos de Update Pendiente factura de Cuota');
                                                             \app\models\Utilities::putMessageLogFile('fpe_id: '.$fpe_id);
                                                             \app\models\Utilities::putMessageLogFile('val_new: '.$val_new);
                                                             \app\models\Utilities::putMessageLogFile('usu_id: '.$usu_id);
                                                             $updatePend =  $mod_facturas_pendientes_est->updateSaldosCuotasNewRegistroOnLine($fpe_id,str_replace([',', '$'],'',$val_new),$usu_id);
                                                             \app\models\Utilities::putMessageLogFile('Resultado Update pendientes facturas: '.$updatePend);
                                                                if($updatePend<=0){
                                                                    throw new Exception('Error al actualizar saldo pendiente cuota');
                                                                }


                                                                $per = $arr_cuotas['data'][$i][2];
                                                                //$val = $arr_cuotas['data'][$i][3];
                                                                $numC = $i + 1;
                                                                //$sumCuota += str_replace([',', '$'],'',$val);

                                                                $modelCuota = new RegistroOnlineCuota();
                                                                $modelCuota->ron_id = $id;
                                                                $modelCuota->rpm_id = $modelReg->rpm_id;
                                                                $modelCuota->roc_num_cuota = "$numC"."/".$numcuotas;
                                                                $modelCuota->roc_porcentaje = str_replace(',','',$per);
                                                                $modelCuota->roc_costo = str_replace([',', '$'],'',$val_new);
                                                                $modelCuota->roc_estado = '1';
                                                                $modelCuota->roc_estado_logico = '1';
                                                                $modelCuota->fpe_id = $fpe_id;
                                                                $modelCuota->roc_vencimiento = $arr_vence[$i];
                                                                $modelCuota->roc_estado_pago = 'P';

                                                                if(!$modelCuota->save()){
                                                                    throw new Exception('Error to Save Payment Registry.');
                                                                }


                                                        } 
                                                        //Si es mayor a el num actual a que tienee pendiente

                                                     }
                                                }

                                            }//if(count($TieneRpM)<=0){
                                            
//comentar 2


                                            //Si no hay registros de pendientes procedo a insertar pendients. 

                                            if(count($TieneRpM)<=0){ //comentar 3

                                                for($i=0; $i < $numcuotas; $i++){

                                                $per = $arr_cuotas['data'][$i][2];
                                                $val = $arr_cuotas['data'][$i][3];
                                                $numC = $i + 1;
                                                $fpe_id = 0;
                                                $fpe_vence = $arr_vence[$i];
                                                if($numC>1){
                                                    \app\models\Utilities::putMessageLogFile('<<<<<<<Registrar nuevo facctura pendiente estudiante');
                                                \app\models\Utilities::putMessageLogFile('fpe_vencimiento: '.$fpe_vence);  
                                                \app\models\Utilities::putMessageLogFile(print_r($arr_vence, true)); 
                                                    $pagospendientesea = $mod_facturas_pendientes_est->crearCabeceraFacturaPendienteEstudiante($per_id,$numC,str_replace([',', '$'],'',$val),'001',$usu_id,$fpe_vence); 
                                                    \app\models\Utilities::putMessageLogFile('fpe_id: '.$pagospendientesea);     
                                                    if(!$pagospendientesea){
                                                        throw new Exception('Error to Save Payment Registry.');
                                                    }

                                                    \app\models\Utilities::putMessageLogFile('fpe_id :>>>>>>:'.$fpe_id );
                                                
                                                    $fpe_id=$pagospendientesea;
                                                }
                                                

                                                $sumCuota += str_replace([',', '$'],'',$val);
                                                $modelCuota = new RegistroOnlineCuota();
                                                $modelCuota->ron_id = $id;
                                                $modelCuota->rpm_id = $modelReg->rpm_id;
                                                $modelCuota->roc_num_cuota = "$numC"."/".$numcuotas;
                                                $modelCuota->roc_porcentaje = str_replace(',','',$per);
                                                $modelCuota->roc_costo = str_replace([',', '$'],'',$val);
                                                $modelCuota->roc_estado = '1';
                                                $modelCuota->roc_estado_logico = '1';
                                                $modelCuota->fpe_id = $fpe_id;

                                                if($numC==1){
                                                      $pfes_banco = 0;
                                                      $pfes_observacion="Pago primera cuota por matricula estudiante ";
                                                      if ($fpago==6 || $fpago==1)
                                                        $modelCuota->roc_estado_pago = "C";
                                                      else  
                                                        $modelCuota->roc_estado_pago = "R";
                                                      
                                                      //procede a registrar datos de pago
                                                      //Se debe registrar datosd e pago ddel estudiante y pendientes por pagar
                                                     //registrar datos del pago de la cuota  en la tabla nueva pagos_factura_estudiante
                                                }else{ $modelCuota->roc_estado_pago = "P";}   

                                                $arr_cuotas_ro[] = str_replace([',', '$'],'',$val);
                                                $modelCuota->roc_vencimiento = $arr_vence[$i];
                                                $arr_vencimiento[] = $modelCuota->roc_vencimiento;


                                                                    if(!$modelCuota->save()){
                                                                        throw new Exception('Error to Save Payment Registry.');
                                                                    }
                                                                    else{

                                                                        if($numC==1){
                                                                          
                                                                                    $pfes_referencia = "";
                                                                                    //$pfes_numero_documento = "";
                                                                                     $confac  = \Yii::$app->db_facturacion;
                                                                                    //Obtener secuencial
                                                                                     $pfes_numero_documento = Secuencias::nuevaSecuencia($confac, 2, 1, 1, 'REC');
                                                                                                     //insertarPagospendientes
                                                                                     $pfes_id_result  = $pagosFacturaEstudiante->crearPagosFacturaEstudiante($est_id,$pfes_referencia,$fpago,
                                                                                                        $pfes_numero_documento,$modelCuota->roc_costo,$pfes_observacion,
                                                                                                        $archivo,'C',$usu_id,$pfes_banco,''
                                                                                                    );
                                                                                    Utilities::putMessageLogFile('>>>>>>>>>>>>>>>>>>>>><Resultado de Id CAbecera de Pago '. $pfes_id_result);
                                                                                                        //detalle de pago de matricula inicial
                                                                                    $resp_detpagofactura = $pagosFacturaEstudiante->insertarDetpagospendientes($pfes_id_result,$modelCuota->roc_id,$modelCuota->roc_costo,$usu_id,$dpfa_estado_pago);
                                                                        }
                                            }
                                        }
                                    } //comentar 4

                               
                                

                            

                            // procesos de creacion de ficha de enrolamiento
                            
                            $model_pes = PlanificacionEstudiante::findOne($model->pes_id); //si
                            $model_pla = Planificacion::findOne($model_pes->pla_id); // si

                            $arrCarrera = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id); //si
                            $eaca_id = $arrCarrera['eaca_id'];
                            $mod_id = $arrCarrera['mod_id'];
                            $uaca_id = $arrCarrera['uaca_id'];
                            $modelModalidad = Modalidad::findOne($mod_id); //si
                            $paca_id = $model_pla->paca_id;
                            $model_persona = Persona::findOne($per_id); //si
                            $model_ciudad = Canton::findOne($model_persona->can_id_domicilio);
                            $model_provincia = Provincia::findOne($model_persona->pro_id_domicilio);
                            $model_ciudadNac = Canton::findOne($model_persona->can_id_nacimiento);
                            $model_PaisNac = Pais::findOne($model_persona->pai_id_nacimiento);
                            $modelProCre = ProgramaCostoCredito::findOne(['eaca_id' => $eaca_id, 'mod_id' => $mod_id, 'pccr_estado' => '1', 'pccr_estado_logico' => '1']);
                            $costCarrera = $modelProCre->pccr_costo_carrera;
                            $costoCredito = $modelProCre->pccr_costo_credito;
                            $creditosCarrera = $modelProCre->pccr_creditos;
                            $titulo = $modelProCre->pccr_titulo;
                            $modelNivelIns = NivelInstruccion::findOne(['nins_id' => $model_persona->nins_id]);
                            $modelFechaGrad = FechaGraduacion::findOne(['paca_id' => $paca_id, 'uaca_id' => $uaca_id, 'fgra_estado' => '1', 'fgra_estado_logico' => '1']);
                            
                            $modelProCre = ProgramaCostoCredito::findOne(['eaca_id' => $eaca_id, 'mod_id' => $mod_id, 'pccr_estado' => '1', 'pccr_estado_logico' => '1']);
                           /* $model_enroll = EnrolamientoAgreement::findOne(['ron_id' => $id, 'rpm_id' => $modelReg->rpm_id, 'per_id' => $per_id, 'eaca_id' => $eaca_id, 'uaca_id' => $uaca_id]);
                            if(!$model_enroll){
                                
                                
                                
                            }*/
                            //pago stripe
                            if($fpago == 6  || $fpago==1){
                                /**
                                *  INI  proceso de debtito - credito pago en línea
                                *si todo esta ok entonces procedemos a realizar en caso de pago por stripe
                                */  
                                \app\models\Utilities::putMessageLogFile('<<<<<<<<<<<<INGRESA A PROCESO DE PAGO EN STRIPE>>>>>>>>>>>>>>><');
                                $statusMsg = ''; 
                                \app\models\Utilities::putMessageLogFile('stripeToken: '. $data['stripeToken']);
                                \app\models\Utilities::putMessageLogFile('name: '. $data['name']);
                                \app\models\Utilities::putMessageLogFile('email: '. $data['email']);
                                \app\models\Utilities::putMessageLogFile('valFrtsPay: '. $data['valFrtsPay']);

                                $payment_id = $statusMsg = ''; 
                                $ordStatus  = 'error'; 

                            if(!empty($data['stripeToken'])){  
                                        $token  = $data['stripeToken']; 
                                        $name   = $data['name']; 
                                        $email  = $data['email']; 
                                        //valor para el pago en el stripe
                                        if($tpago == 1 || $tpago == 2){
                                            $valFrtsPay      = $totaldesc;
                                        }else{
                                            $valFrtsPay      = $data['cuotaini']; 
                                        }
                                

                                        /******************************************************************/
                                        /********** PARA DESARROLLO  **************************************/
                                        /******************************************************************/
                                       /* $stripe = array(
                                            'secret_key'      => 'sk_test_51HrVkKC4VyMkdPFRrDhbuQLABtvVq3tfZ8c3E3fm55Q7kg5anz6fqO5qrlPBVu7fDc9XVWGTb55M6TiIq4hwHz8J00rVFgisaj',
                                            'publishable_key' => 'pk_test_51HrVkKC4VyMkdPFRZ5aImiv4UNRIm1N7qh2VWG5YMcXJMufmwqvCVYAKSZVxvsjpP6PbjW4sSrc8OKrgfNsrmswt00OezUqkuN',
                                        );*/
                                
                                        /******************************************************************/
                                        /********** PARA PRODUCCION  **************************************/
                                        /******************************************************************/
                                        /*
                                        $stripe = array(
                                            'secret_key'      => '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',
                                            'publishable_key' => 'pk_live_51HrVkKC4VyMkdPFRjqnwytVZZb552sp7TNEmQanSA78wA1awVHIDp94YcNKfa66Qxs6z2E73UGJwUjWN2pcy9nWl008QHsVt3Q',
                                        );
                                        */

                                        //Se hace invocacion a libreria de stripe que se encuentra en el vendor
                                        \Stripe\Stripe::setApiKey(Yii::$app->params["secret_key"]);

                                        // Add customer to stripe                         
                                        $customer = \Stripe\Customer::create(array( 
                                            'email'   => $email, 
                                            'source'  => $token ,
                                        )); 

                                     if($customer){
                                        // Convert price to cents 
                                        //$valor_inscripcion = 120;
                                        //$valFrtsPay =  120;
                                        $itemPriceCents = ($valFrtsPay*100); 

                                        $charge = \Stripe\Charge::create(array( 
                                        'customer'    => $customer->id, 
                                        'amount'      => $itemPriceCents, 
                                        'currency'    => "usd", 
                                        'description' => "Pago de primera cuota"
                                        )); 

                                            if($charge){ 
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
                                                                $statusMsg = 'Your Payment has been Successful!'; 

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
                            \app\models\Utilities::putMessageLogFile('<<<<<<<<<<<<FIN A PROCESO DE PAGO EN STRIPE>>>>>>>>>>>>>>><');
                            \app\models\Utilities::putMessageLogFile('RESULTADO DEL PAGO: '.$statusMsg);
                           /**
                            * Fin proceso de debtito - credito pago en línea
                            */
                            if ($payment_status == 'succeeded') { // pago exitoso

                                  

                                }else{//si no se realizo pago esxito
                                        $transaction->rollback();
                                        $con->rollback();
                                        $message = array(
                                        "wtmessage" => Yii::t("notificaciones",$statusMsg),
                                        "title" => Yii::t('jslang', 'error'),
                                        );
                                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                                }
                            }
                        }
            //Envio de notificaciones al estudiante y Financiero
            if($tpago== 1|| $tpago ==2){
                $valorCorreo = $totaldesc;
            }else{
                $valorCorreo=$data['total'];

            }
            //PAra mostrar el  metodo de pago
            if($tpago == 1){
                $tpagoMail = "Pago total de Carrera";
            }
            if($tpago == 2){
                $tpagoMail = "Pago total del Periodo";
            }
            if($tpago == 3){
                $tpagoMail = "Crédito Directo";
            }

             $modelFP = new FormaPago();
             $arr_forma_pago = $modelFP->consultarFormaPagoById($fpago);
             $model_persona1 = Persona::findOne($per_id); 
             $asunto = Academico::t('matriculacion',"Online Registration");
             $body = Utilities::getMailMessage("enrollment", array("[[user]]" => $model_persona->per_pri_nombre . " " . $model_persona->per_pri_apellido, 
                "[[periodo]]" => $model_pla->pla_periodo_academico, 
                "[[modalidad]]" => $modelModalidad->mod_nombre, 
                "[[total]]" => $valorCorreo, 
                "[[formapago]]" => $arr_forma_pago[0]['value'],
                "[[metodopago]]" => $tpagoMail,
            ), 
            Yii::$app->language, Yii::$app->basePath . "/modules/academico");
            $titulo_mensaje = Academico::t('matriculacion',"Online Enrollment Record");


            \app\models\Utilities::putMessageLogFile('Datos de Notificacionde Correo Didimo');
             \app\models\Utilities::putMessageLogFile('Persona Id');
              \app\models\Utilities::putMessageLogFile($per_id);
             \app\models\Utilities::putMessageLogFile('>>>>>>>>>adminEmail>>>>>>>>>');
            \app\models\Utilities::putMessageLogFile(Yii::$app->params["adminEmail"]);
            \app\models\Utilities::putMessageLogFile('>>>>>>>>>Persona>>>>>>>>>');
            \app\models\Utilities::putMessageLogFile($model_persona1->per_correo);
              \app\models\Utilities::putMessageLogFile('>>>>>>>>>Nombre Persona>>>>>>>>>');
               \app\models\Utilities::putMessageLogFile($model_persona1->per_pri_nombre);
             \app\models\Utilities::putMessageLogFile('>>>>>>>>>colecturia>>>>>>>>>');
            \app\models\Utilities::putMessageLogFile(Yii::$app->params["colecturia"]);

            Utilities::sendEmail($titulo_mensaje,
                 Yii::$app->params["adminEmail"],  
                 [$model_persona1->per_correo =>  $model_persona1->per_pri_nombre . " " . $model_persona1->per_pri_apellido], 
                 $asunto, 
                 $body);
                           

              /**
              * Envio de mail al colecturia
              **/
            $body_colecturia =  Utilities::getMailMessage("enrollmentcolecturia", array("[[periodo]]" => $model_pla->pla_periodo_academico, "[[modalidad]]" => $modelModalidad->mod_nombre), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
            Utilities::sendEmail($titulo_mensaje, Yii::$app->params["adminEmail"], [Yii::$app->params["colecturia"] => "Supervisor"], $asunto, $body_colecturia); 


                            //Realizo commit del proceso 
                            $transaction->commit();
                            $con->commit();
                            //Enviar Notific
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."), //dbefin
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                            //return $this->redirect(['index']);
                            //$transaction->commit();
                            //return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                        }else{
                            throw new Exception('Error to Save Information.');
                        }
                    } catch (Exception $ex) {
                        $transaction->rollback();
                        $con->rollback();
                        $msg = ($error)?($ex->getMessage()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                        //$msg = $ex->getMessage();
                        $message = array(
                            "wtmessage" => $msg,
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                }
    }

    

    public function actionUpdate($id){
        $model = RegistroOnline::findOne($id);
        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File. Try again.")]);
                }
                //Recibe Parámetros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $namefile= $data["name_file"];
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if ($typeFile == 'jpeg' || $typeFile == 'png' || $typeFile == 'jpg') {
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "pagosmatricula/" . $namefile . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) .". Try again.")]);
                    }
                } else {
                    return json_encode(['error' => Yii::t("notificaciones", "Error to process File ". basename($files['name']) ." Only format Images jpg, jpeg, png. Try again.")]);
                }
            }
            $transaction = Yii::$app->db_academico->beginTransaction();
            $error = false;
            try{
                $archivo = "pagosmatricula/" . $data["archivo"];
                $model_ron = RegistroOnline::findOne(['ron_id' => $id, 'per_id' => $per_id, 'ron_estado' => '1', 'ron_estado_logico' => '1']);
                $model_pes = PlanificacionEstudiante::findOne(['pes_id' => $model_ron->pes_id, 'per_id' => $per_id, 'pes_estado' => '1', 'pes_estado_logico' => '1']);
                $model_rpm = RegistroPagoMatricula::findOne(['rpm_id' => $data['rpm_id'],'per_id' => $per_id, 'pla_id' => $model_pes->pla_id, 'ron_id' => $id, 'rpm_estado' => '1', 'rpm_estado_logico' => '1']);
                $model_rpm->rpm_archivo = "pagosmatricula/" . $archivo;
                $model_rpm->rpm_estado_aprobacion = "0"; /** 0->No revisado, 1->Aprobado, 2->Rechazado */
                $model_rpm->rpm_estado_generado = "1"; /**0->por pagar, 1->por revisar, 2->pagado */
                $model_rpm->rpm_fecha_modificacion = $fecha_modificacion;

                if ($model_rpm->save()) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                }else{
                    throw new Exception('Error to Save Information.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionView($id){
        $model = RegistroOnline::findOne($id);
        $data = Yii::$app->request->get();
        $rama_id = $data['rama_id'];
        $modelRegAd = RegistroAdicionalMaterias::findOne(['rama_id' => $rama_id, 'rama_estado' => '1', 'rama_estado_logico' => '1',]);

        $_SESSION['JSLANG']['JAN'] = Academico::t('registro', 'JAN');
        $_SESSION['JSLANG']['FEB'] = Academico::t('registro', 'FEB');
        $_SESSION['JSLANG']['MAR'] = Academico::t('registro', 'MAR');
        $_SESSION['JSLANG']['APR'] = Academico::t('registro', 'APR');
        $_SESSION['JSLANG']['MAY'] = Academico::t('registro', 'MAY');
        $_SESSION['JSLANG']['JUN'] = Academico::t('registro', 'JUN');
        $_SESSION['JSLANG']['JUL'] = Academico::t('registro', 'JUL');
        $_SESSION['JSLANG']['AUG'] = Academico::t('registro', 'AUG');
        $_SESSION['JSLANG']['SEP'] = Academico::t('registro', 'SEP');
        $_SESSION['JSLANG']['OCT'] = Academico::t('registro', 'OCT');
        $_SESSION['JSLANG']['NOV'] = Academico::t('registro', 'NOV');
        $_SESSION['JSLANG']['DEC'] = Academico::t('registro', 'DEC');
        $_SESSION['JSLANG']['The value of the first payment must be greater than or equal to'] = Academico::t('registro', 'The value of the first payment must be greater than or equal to');
        $_SESSION['JSLANG']['The value of the first payment must be less than or equal to'] = Academico::t('registro', 'The value of the first payment must be less than or equal to');
        $_SESSION['JSLANG']['To Continue you must accept terms and conditions'] = Academico::t('registro', 'To Continue you must accept terms and conditions');
        $_SESSION['JSLANG']['Please attach a file in format png or jpeg'] = Academico::t('registro', 'Please attach a file in format png or jpeg');

        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        
        $emp_perMod = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $per_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
        $grol_id = 32; //grol_id = 32 es estudiante
        $usagrolMod = NULL;
        $esEstu = FALSE;
        if($emp_perMod){
            $usagrolMod = UsuaGrolEper::findOne(['eper_id' => $emp_perMod->eper_id, 'usu_id' => $usu_id, 'grol_id' => $grol_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
        }
        if($usagrolMod) $esEstu = TRUE;

        $model_pes = PlanificacionEstudiante::findOne($model->pes_id);
        $model_pla = Planificacion::findOne($model_pes->pla_id);
        $emp_id = Yii::$app->session->get("PB_idempresa");

        $mod_periodo = new PlanificacionEstudiante();
        $arrCarrera = $mod_periodo->consultaracarreraxmallaaut($per_id);

        //$arrCarrera = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id);
        $modelFP = new FormaPago();
        $arr_forma_pago = $modelFP->consultarFormaPago();
        unset($arr_forma_pago[0]);
        unset($arr_forma_pago[1]);
        unset($arr_forma_pago[2]);
        unset($arr_forma_pago[6]);
        unset($arr_forma_pago[7]);
        unset($arr_forma_pago[8]);
        // get Credits and get Cost x Credit
        $eaca_id = $arrCarrera['eaca_id'];
        $mod_id = $arrCarrera['mod_id'];
        $uaca_id = $arrCarrera['uaca_id'];
        $arr_cost_mat = RegistroOnlineItem::getTotalCreditsByRegister($id, $rama_id);
        Utilities::putMessageLogFile('eaca_id'. $eaca_id);
        $modelProCre = ProgramaCostoCredito::findOne(['eaca_id' => $eaca_id, 'mod_id' => $mod_id, 'pccr_estado' => '1', 'pccr_estado_logico' => '1']);
        $costCarrera = $modelProCre->pccr_costo_carrera;
        $costoCredito = $modelProCre->pccr_costo_credito;
        $creditosCarrera = $modelProCre->pccr_creditos;
        $arr_forma_pago = ArrayHelper::map($arr_forma_pago, "id", "value");
        $per_id = ($esEstu)?($per_id):($model->per_id); 
        $model_ron = RegistroOnline::findOne(['ron_id' => $id, 'per_id' => $per_id, 'ron_estado' => '1', 'ron_estado_logico' => '1']);
        $model_pes = PlanificacionEstudiante::findOne(['pes_id' => $model_ron->pes_id, 'per_id' => $per_id, 'pes_estado' => '1', 'pes_estado_logico' => '1']);
        $model_rpm = RegistroPagoMatricula::findOne(['rpm_id' => $modelRegAd->rpm_id,'per_id' => $per_id, 'pla_id' => $model_pes->pla_id, 'rpm_estado' => '1', 'rpm_estado_logico' => '1']);
        
        if(!$model_rpm){
            return $this->redirect('index');
        }

        $value_total = number_format($model_rpm->rpm_total, 2, '.', ',');
        $model_roc = RegistroOnlineCuota::findAll(['rpm_id' => $modelRegAd->rpm_id, 'ron_id' => $id, 'roc_estado' => '1', 'roc_estado_logico' => '1']);
        $dataValue = array();
        $con = 0;
        //$model_can = CancelacionRegistroOnline::findOne(['ron_id' => $id, 'cron_estado' => '1', 'cron_estado_logico' => '1']);
        $model_can = false;
        if($model_can){
            $modelEnroll = EnrolamientoAgreement::findOne(['ron_id' => $id, 'rpm_id' => $modelRegAd->rpm_id, 'per_id' => $per_id, 'eaca_id' => $eaca_id, 'uaca_id' => $uaca_id, 'eagr_estado' => '1', 'eagr_estado_logico' => '1',]);
            $value_total = number_format($modelEnroll->eagr_costo_programa_est, 2, '.', ',');
            /*foreach($model_roc as $key => $value){
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
            foreach($model_roc as $key => $value){
                $con++;
                $dataValue[] = ['Id' => $con, 'Pago' => Academico::t('matriculacion',"Payment") . ' #' . $con, 'Vencimiento' => $value->roc_vencimiento, 'Porcentaje' => $value->roc_porcentaje, 'Valor' => '$'.(number_format($value->roc_costo, 2, '.', ','))];
            }*/
        }else{
            foreach($model_roc as $key => $value){
                $con++;
                $dataValue[] = ['Id' => $con, 'Pago' => Academico::t('matriculacion',"Payment") . ' #' . $con, 'Vencimiento' => $value->roc_vencimiento, 'Porcentaje' => $value->roc_porcentaje, 'Valor' => '$'.(number_format($value->roc_costo, 2, '.', ','))];
            }
        }
        
        $dataProvider = new ArrayDataProvider([
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
            'model' => $model,
            'estudiante' => $model_pes->pes_nombres,
            'carrera' => $arrCarrera['Carrera'],
            'periodo' => $model_pla->pla_periodo_academico,
            'arr_forma_pago' => $arr_forma_pago,
            'arr_credito' => $this->getCreditoPay(),
            'id' => $id,
            'cuotas' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5',],
            'dataGrid' => $dataProvider,
            'costCarrera' => (number_format($costCarrera, 2, '.', ',')),
            'costoCredito' => $costoCredito,
            'creditosCarrera' => $creditosCarrera,
            'creditoItem' => $arr_cost_mat['Credito'],
            'costoItem' => (number_format($arr_cost_mat['Costo'], 2, '.', ',')),
            'value_credit' => $model_rpm->rpm_tipo_pago,
            'value_payment' => $model_rpm->fpag_id,
            'lnk_download' => $model_rpm->rpm_archivo,
            'value_total' => $value_total,
            'value_interes' => number_format($model_rpm->rpm_interes, 2, '.', ','),
            'value_financiamiento' => number_format($model_rpm->rpm_financiamiento, 2, '.', ','),
            'value_cuotas' => $con,
            'value_pago_inicial' => $dataValue[0]['Valor'],
            'value_terminos' => $model_rpm->rpm_acepta_terminos,
            'rpm_id' => $model_rpm->rpm_id,
            'rama_id' => $rama_id,
        ]);
    }

    public function actionDownloadpago(){
        $report = new ExportFile();

        $data = Yii::$app->request->get();

        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        
        $emp_perMod = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $per_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
        $grol_id = 32; //grol_id = 32 es estudiante
        $usagrolMod = NULL;
        $esEstu = FALSE;
        if($emp_perMod){
            $usagrolMod = UsuaGrolEper::findOne(['eper_id' => $emp_perMod->eper_id, 'usu_id' => $usu_id, 'grol_id' => $grol_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
        }
        if($usagrolMod) $esEstu = TRUE;
        $rpm_id = $data['id'];
        $registro_pago_matricula = RegistroPagoMatricula::findOne(["rpm_id" => $rpm_id, 'rpm_estado' => '1', 'rpm_estado_logico' => '1']);
        $per_id = ($esEstu)?($per_id):($registro_pago_matricula->per_id); 
        if($esEstu){
            if(Yii::$app->session->get("PB_perid") != $registro_pago_matricula->per_id){
                exit();
            }
        }

        $rpm_id = $data['rpm_id'];
        $file = Yii::$app->basePath . Yii::$app->params['documentFolder'] . $registro_pago_matricula->rpm_archivo;
        
        if (Yii::$app->session->get('PB_isuser')) {
            $arrfile = explode(".", $file);
            $typeImage = $arrfile[count($arrfile) - 1];
            if (file_exists($file)) {
                header('Pragma: public');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false);
                header("Content-type: ". Utilities::mimeContentType($file));
                header('Content-Disposition: attachment; filename="payment_' . time() . '.'.$typeImage.'";');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($file));
                readfile($file);
            }
        }
        exit();
    }

    public function actionDownloadenroll(){
        try {
            $id = $_GET['id'];
            $rpm_id = $_GET['rpm_id'];
            $per_id = Yii::$app->session->get("PB_perid"); 
            $usu_id = Yii::$app->session->get("PB_iduser");
            $emp_id = Yii::$app->session->get("PB_idempresa");
            $emp_perMod = EmpresaPersona::findOne(['emp_id' => $emp_id, 'per_id' => $per_id, 'eper_estado' => '1', 'eper_estado_logico' => '1']);
            $grol_id = 32; //grol_id = 32 es estudiante
            $usagrolMod = NULL;
            $esEstu = FALSE;
            if($emp_perMod){
                $usagrolMod = UsuaGrolEper::findOne(['eper_id' => $emp_perMod->eper_id, 'usu_id' => $usu_id, 'grol_id' => $grol_id, 'ugep_estado' => '1', 'ugep_estado_logico' => '1']);
            }
            if($usagrolMod) $esEstu = TRUE;
            $model = RegistroOnline::findOne($id);
            $per_id = ($esEstu)?($per_id):($model->per_id); 
            $arrCarrera = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id);
            $eaca_id = $arrCarrera['eaca_id'];
            $mod_id = $arrCarrera['mod_id'];
            $uaca_id = $arrCarrera['uaca_id'];
            $model_enroll = EnrolamientoAgreement::findOne(['ron_id' => $id, 'rpm_id' => $rpm_id, 'per_id' => $per_id, 'eaca_id' => $eaca_id, 'uaca_id' => $uaca_id, 'eagr_estado' => '1', 'eagr_estado_logico' => '1',]);
            $arrItemPrices = RegistroPagoMatricula::getItemsPrice();
            $rep = new ExportFile();
            //$this->layout = false;
            $this->layout = '@modules/academico/views/registro/enrollment_main';
            //setlocale(LC_TIME, 'es_CO.UTF-8');

            $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
            $rep->createReportPdf(
                    $this->render('@modules/academico/views/registro/enrollment_body', [
                        "fpag_id" => $model_enroll->fpag_id,
                        "nombre_uni" => $model_enroll->eagr_nombre_uni,
                        "telefono_uni" => $model_enroll->eagr_telefono_uni,
                        "direccion_uni" => $model_enroll->eagr_direccion_uni,
                        "apellidos_est" => $model_enroll->eagr_apellidos_est,
                        "nombres_est" => $model_enroll->eagr_nombres_est,
                        "direccion_est" => $model_enroll->eagr_direccion_est,
                        "ciudad_est" => $model_enroll->eagr_ciudad_est,
                        "estado_est" => $model_enroll->eagr_estado_est,
                        "sexo_est" => $model_enroll->eagr_sexo_est,
                        "ciudadania_est" => $model_enroll->eagr_ciudadania_est,
                        "fnacimiento_est" => $model_enroll->eagr_fnacimiento_est,
                        "lnacimiento_est" => $model_enroll->eagr_lugar_nac_est,
                        "correo_est" => $model_enroll->eagr_email_est,
                        "telefono_est" => $model_enroll->eagr_telefono_est,
                        "programa_est" => $model_enroll->eagr_programa_est,
                        "creditos_est" => $model_enroll->eagr_pro_creditos_est,
                        "titulo_est" => $model_enroll->eagr_pro_titulo_est,
                        "finicio_est" => $model_enroll->eagr_fecha_inicio_est,
                        "fgraduacion_est" => $model_enroll->eagr_fecha_graduacion_est,
                        "periodoAcad" => $model_enroll->eagr_periodo_academico_est,
                        "pinteres_est" => $model_enroll->eagr_porcentaje_anual_est,
                        "financiamiento" => $model_enroll->eagr_financiamiento_est,
                        "costoProgCarrera_est" => $model_enroll->eagr_costo_carrera_est,
                        "costoCarrera_est" => $model_enroll->eagr_costo_programa_est,
                        "pagoPrograma_est" => $model_enroll->eagr_pago_programa_est,
                        "cuota1" => $model_enroll->eagr_primera_cuota_est,
                        "cuota2" => $model_enroll->eagr_segunda_cuota_est,
                        "cuota3" => $model_enroll->eagr_tercera_cuota_est,
                        "cuota4" => $model_enroll->eagr_cuarta_cuota_est,
                        "cuota5" => $model_enroll->eagr_quinta_cuota_est,
                        "vencimiento1" => $model_enroll->eagr_primera_ven_est,
                        "vencimiento2" => $model_enroll->eagr_segunda_ven_est,
                        "vencimiento3" => $model_enroll->eagr_tercera_ven_est,
                        "vencimiento4" => $model_enroll->eagr_cuarta_ven_est,
                        "vencimiento5" => $model_enroll->eagr_quinta_ven_est,
                        "mi_est" => $model_enroll->eagr_mi_est,
                        "educacion_est" => $model_enroll->eagr_educacion_est,
                        "zipcode_est" => $model_enroll->eagr_zipcode_est,
                        "firma_par" => $model_enroll->eagr_firma_fecha_par,
                        "firma_dec" => $model_enroll->eagr_firma_dec,
                        "firma_est" => $model_enroll->eagr_firma_est,
                        "ffirma_par" => $model_enroll->eagr_firma_fecha_par,
                        "ffirma_dec" => $model_enroll->eagr_firma_fecha_dec,
                        "ffirma_est" => $model_enroll->eagr_firma_fecha_est,
                        "itemsPrices" => $arrItemPrices,
                        "idPriceTuition" => [4,5,6],
                        "idPriceFees" => [1,2,3],
                    ])
            );
            $rep->mpdf->Output('ENROLLMENT_' . time() . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
            //exit;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function actionCancelregistration(){
        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $data = Yii::$app->request->get();
        $model = new CancelacionRegistroOnline();

        if ($data['PBgetFilter']) {
            $search = $data['search'];
            $periodo = $data['periodo'];
            $modalidad = $data['mod_id'];
            $estado = $data['estado'];
            return $this->renderPartial('cancel-grid', [ 
                'model' => $model->getAllListCancelGrid($search, $modalidad, $estado, $periodo, true),
                'refund' => true,
            ]);
        }
        $arr_status = [
            -1 => Academico::t("matriculacion", "-- Select Status --"), 
            0 => Academico::t("matriculacion", "To be Approved"), 
            1 => Academico::t("matriculacion", "To confirm Refund"),
            2 => Academico::t("matriculacion", "Refund Applied"),
            3 => Academico::t("matriculacion", "Approved"),            
        ];
        $arr_pla_per = Planificacion::getPeriodosAcademico();
        $arr_modalidad = Planificacion::find()
                ->select(['m.mod_id', 'm.mod_nombre'])
                ->join('inner join', 'modalidad m')
                ->where('pla_estado_logico = 1 and pla_estado = 1 and m.mod_estado =1 and m.mod_estado_logico = 1')
                ->asArray()
                ->all();

        return $this->render('cancel-list', [
            'periodoAcademico' => array_merge([0 => Academico::t("matriculacion", "-- Select Academic Period --")], ArrayHelper::map($arr_pla_per, "pla_id", "pla_periodo_academico")),
            'arr_modalidad' => array_merge([0 => Academico::t("matriculacion", "-- Select Modality --")], ArrayHelper::map($arr_modalidad, "mod_id", "mod_nombre")),
            'model' => $model->getAllListCancelGrid(NULL, NULL, NULL, NULL, true),
            'arr_status' => $arr_status,
            'refund' => true,
        ]);
    }

    public function actionCancelregisterapr(){
        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $data = Yii::$app->request->get();
        $model = new CancelacionRegistroOnline();

        if ($data['PBgetFilter']) {
            $search = $data['search'];
            $periodo = $data['periodo'];
            $modalidad = $data['mod_id'];
            $estado = $data['estado'];
            return $this->renderPartial('cancel-grid', [
                'model' => $model->getAllListCancelAcademicoGrid($search, $modalidad, $estado, $periodo, true),
            ]);
        }
        $arr_status = [
            -1 => Academico::t("matriculacion", "-- Select Status --"), 
            0 => Academico::t("matriculacion", "To be Approved"), 
            1 => Academico::t("matriculacion", "To confirm Refund"),
            2 => Academico::t("matriculacion", "Refund Applied"),
            3 => Academico::t("matriculacion", "Approved"),
        ];
        $arr_pla_per = Planificacion::getPeriodosAcademico();
        $arr_modalidad = Planificacion::find()
                ->select(['m.mod_id', 'm.mod_nombre'])
                ->join('inner join', 'modalidad m')
                ->where('pla_estado_logico = 1 and pla_estado = 1 and m.mod_estado =1 and m.mod_estado_logico = 1')
                ->asArray()
                ->all();

        return $this->render('cancel-list', [
            'periodoAcademico' => array_merge([0 => Academico::t("matriculacion", "-- Select Academic Period --")], ArrayHelper::map($arr_pla_per, "pla_id", "pla_periodo_academico")),
            'arr_modalidad' => array_merge([0 => Academico::t("matriculacion", "-- Select Modality --")], ArrayHelper::map($arr_modalidad, "mod_id", "mod_nombre")),
            'model' => $model->getAllListCancelAcademicoGrid(NULL, NULL, NULL, NULL, true),
            'arr_status' => $arr_status,
            'refund' => false,
        ]);
    }

    public function actionViewcancel(){
        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $data = Yii::$app->request->get();
        $cron_id = $data['id'];
        $modelCancel = CancelacionRegistroOnline::findOne($cron_id);
        $modelRon = RegistroOnline::findOne($modelCancel->ron_id);
        $matriculacion_model = new Matriculacion();
        $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($modelRon->per_id, $modelRon->pes_id);
        $model = $modelCancel->getAllInfoCancelGrid(NULL, NULL, NULL, NULL, $modelRon->per_id, true);
        return $this->render('viewcancel', [
            'data_student' => $data_student,
            'model' => $model,
        ]);
    }


    /***
    * Para eregistrar en pago por stripe
    * Didimo Zamora
    * 05/03/2021
    */
    public function actionAprobarcancel(){
        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db_academico->beginTransaction();
            $error = false;
            try{
                $model = CancelacionRegistroOnline::findOne($data['id']);
                $arrModItems = CancelacionRegistroOnlineItem::findAll(['cron_id' => $model->cron_id, 'croi_estado' => '1', 'croi_estado_logico' => '1']);
                $modelRon = RegistroOnline::findOne($model->ron_id);
                $model->cron_estado_cancelacion = '1';
                $model->cron_fecha_modificacion = $fecha_modificacion;
                $model->cron_aprueba = $usu_id;
                if ($model->save()) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    $subjects = "";
                    foreach($arrModItems as $key => $item){
                        $it = $item->roi_id;
                        $modItem = RegistroOnlineItem::findOne($it);
                        $subjects .= $modItem->roi_materia_nombre . " (".'$' . (number_format(($modItem->roi_costo), 2, '.', ',')) .")" . "<br />";
                    }
                    $modelPersona = Persona::findOne($model->per_id);
                    $user_names_est = $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido;
                    $matriculacion_model = new Matriculacion();
                    $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($modelRon->per_id, $modelRon->pes_id);
                    $receiveMail = Yii::$app->params["colecturia"];
                    $user_names = "Lords";
                    $template = "approvecancel";
                    $templateEst = "approvecancelEst";
                    
                    $body = Utilities::getMailMessage($template, array(
                                "[[user]]" => $user_names,
                                "[[nombres]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido,
                                "[[dni]]" => (($modelPersona->per_cedula != "")?$modelPersona->per_cedula:$modelPersona->per_pasaporte),
                                "[[periodo]]" => $data_student["pla_periodo_academico"],
                                "[[modalidad]]" => $data_student["mod_nombre"],
                                "[[subjects]]" => $subjects
                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    $titulo_mensaje = Academico::t("matriculacion", "Online Registration Cancellation");

                    $from = Yii::$app->params["adminEmail"];

                    $to = array(
                        "0" => $receiveMail,
                    );
                    /*$files = array(
                        "0" => Yii::$app->basePath . Yii::$app->params["documentFolder"] . "pagosmatricula/" . $data["file"],
                    );*/
                    $toest = $modelPersona->per_correo;
                    $files = array();
                    $asunto = Academico::t("matriculacion", "Cancellation Request Approval");
                    
                    $bodyest = Utilities::getMailMessage($templateEst, array(
                                "[[user]]" => $user_names_est,
                                "[[periodo]]" => $data_student["pla_periodo_academico"],
                                "[[modalidad]]" => $data_student["mod_nombre"],
                                "[[subjects]]" => $subjects
                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");

                    $transaction->commit();
                    Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);
                    Utilities::sendEmail($titulo_mensaje, $from, $toest, $asunto, $bodyest, $files);
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                }else{
                    throw new Exception('Error to Save Information.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionConfirmrefund(){
        $per_id = Yii::$app->session->get("PB_perid"); 
        $usu_id = Yii::$app->session->get("PB_iduser");
        $emp_id = Yii::$app->session->get("PB_idempresa");
        $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $transaction = Yii::$app->db_academico->beginTransaction();
            $error = false;
            try{
                $model = CancelacionRegistroOnline::findOne($data['id']);
                $arrModItems = CancelacionRegistroOnlineItem::findAll(['cron_id' => $model->cron_id, 'croi_estado' => '1', 'croi_estado_logico' => '1']);
                $modelRon = RegistroOnline::findOne($model->ron_id);
                $model->cron_estado_cancelacion = '2';
                $model->cron_fecha_modificacion = $fecha_modificacion;
                $model->cron_confirma = $usu_id;
                $modelRon->ron_estado_cancelacion = '0';
                if (!$modelRon->save()) {
                    throw new Exception('Error to Save Online Register.');
                }
                if ($model->save()) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    $subjects = "";
                    $totalRefund = 0;
                    foreach($arrModItems as $key => $item){
                        $it = $item->roi_id;
                        $modItem = RegistroOnlineItem::findOne($it);
                        $subjects .= $modItem->roi_materia_nombre . " (".'$' . (number_format(($modItem->roi_costo), 2, '.', ',')) .")" . "<br />";
                        $totalRefund += $modItem->roi_costo;
                    }
                    $modelPersona = Persona::findOne($model->per_id);
                    $user_names_est = $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido;
                    $matriculacion_model = new Matriculacion();
                    $data_student = $matriculacion_model->getDataStudenFromRegistroOnline($modelRon->per_id, $modelRon->pes_id);
                    $receiveMail = Yii::$app->params["colecturia"];
                    $user_names = "Lords";
                    $template = "confirmrefund";
                    $templateEst = "confirmrefundEst";

                    // creando hoja de enrolamiento
                    $arrCarrera = RegistroPagoMatricula::getCarreraByPersona($model->per_id, $emp_id);
                    $eaca_id = $arrCarrera['eaca_id'];
                    $mod_id = $arrCarrera['mod_id'];
                    $uaca_id = $arrCarrera['uaca_id'];
                    $model_enroll = EnrolamientoAgreement::findOne(['ron_id' => $modelRon->ron_id, 'rpm_id' => $model->rpm_id, 'per_id' => $model->per_id, 'eaca_id' => $eaca_id, 'uaca_id' => $uaca_id]);
                    $numCuotas = $model_enroll->eagr_pago_programa_est;
                    $totalPago = ($model_enroll->eagr_costo_programa_est) - $totalRefund;
                    $cuota1 = $totalPago / $numCuotas;
                    $sumtmp = 0;
                    $costCuota = [NULL, NULL, NULL, NULL, NULL];
                    for($i=0; $i<$numCuotas; $i++){
                        $costCuota[$i] = $cuota1;
                        if(( $i + 1 ) == $numCuotas){
                            $costCuota[$i] = $totalPago - $sumtmp;
                        }
                        $sumtmp += $cuota1;
                    }
                    
                    $newmodel_enroll = new EnrolamientoAgreement();
                    $newmodel_enroll->ron_id = $model_enroll->ron_id;
                    $newmodel_enroll->pccr_id = $model_enroll->pccr_id;
                    $newmodel_enroll->per_id = $model_enroll->per_id;
                    $newmodel_enroll->eaca_id = $model_enroll->eaca_id;
                    $newmodel_enroll->uaca_id = $model_enroll->uaca_id;
                    $newmodel_enroll->fpag_id = $model_enroll->fpag_id;
                    $newmodel_enroll->rpm_id = $model_enroll->rpm_id;
                    $newmodel_enroll->eagr_nombre_uni = $model_enroll->eagr_nombre_uni;
                    $newmodel_enroll->eagr_telefono_uni = $model_enroll->eagr_telefono_uni;
                    $newmodel_enroll->eagr_direccion_uni = $model_enroll->eagr_direccion_uni;
                    $newmodel_enroll->eagr_apellidos_est = $model_enroll->eagr_apellidos_est;
                    $newmodel_enroll->eagr_nombres_est = $model_enroll->eagr_nombres_est;
                    $newmodel_enroll->eagr_direccion_est = $model_enroll->eagr_direccion_est;
                    $newmodel_enroll->eagr_ciudad_est = $model_enroll->eagr_ciudad_est;
                    $newmodel_enroll->eagr_estado_est = $model_enroll->eagr_estado_est;
                    $newmodel_enroll->eagr_sexo_est = $model_enroll->eagr_sexo_est;
                    $newmodel_enroll->eagr_ciudadania_est = $model_enroll->eagr_ciudadania_est;
                    $newmodel_enroll->eagr_fnacimiento_est = $model_enroll->eagr_fnacimiento_est;
                    $newmodel_enroll->eagr_lugar_nac_est = $model_enroll->eagr_lugar_nac_est;
                    $newmodel_enroll->eagr_email_est = $model_enroll->eagr_email_est;
                    $newmodel_enroll->eagr_telefono_est = $model_enroll->eagr_telefono_est; //$model_persona->per_celular;
                    $newmodel_enroll->eagr_programa_est = $model_enroll->eagr_programa_est;
                    $newmodel_enroll->eagr_pro_creditos_est = $model_enroll->eagr_pro_creditos_est;
                    $newmodel_enroll->eagr_pro_titulo_est = $model_enroll->eagr_pro_titulo_est;
                    $newmodel_enroll->eagr_fecha_inicio_est = $model_enroll->eagr_fecha_inicio_est;
                    $newmodel_enroll->eagr_fecha_graduacion_est = $model_enroll->eagr_fecha_graduacion_est;
                    $newmodel_enroll->eagr_periodo_academico_est = $model_enroll->eagr_periodo_academico_est;
                    $newmodel_enroll->eagr_porcentaje_anual_est = $model_enroll->eagr_porcentaje_anual_est;
                    $newmodel_enroll->eagr_financiamiento_est = $model_enroll->eagr_financiamiento_est;
                    $newmodel_enroll->eagr_costo_carrera_est = $model_enroll->eagr_costo_carrera_est;
                    $newmodel_enroll->eagr_costo_programa_est =  $totalPago; // restar de lo que se removio
                    $newmodel_enroll->eagr_pago_programa_est = $numCuotas;
                    $newmodel_enroll->eagr_primera_cuota_est = $costCuota[0];
                    $newmodel_enroll->eagr_segunda_cuota_est = $costCuota[1];
                    $newmodel_enroll->eagr_tercera_cuota_est = $costCuota[2];
                    $newmodel_enroll->eagr_cuarta_cuota_est = $costCuota[3];
                    $newmodel_enroll->eagr_quinta_cuota_est = $costCuota[4];
                    $newmodel_enroll->eagr_primera_ven_est = $model_enroll->eagr_primera_ven_est;
                    $newmodel_enroll->eagr_segunda_ven_est = $model_enroll->eagr_segunda_ven_est;
                    $newmodel_enroll->eagr_tercera_ven_est = $model_enroll->eagr_tercera_ven_est;
                    $newmodel_enroll->eagr_cuarta_ven_est = $model_enroll->eagr_cuarta_ven_est;
                    $newmodel_enroll->eagr_quinta_ven_est = $model_enroll->eagr_quinta_ven_est;
                    $newmodel_enroll->eagr_mi_est = $model_enroll->eagr_mi_est;
                    $newmodel_enroll->eagr_educacion_est = $model_enroll->eagr_educacion_est;
                    $newmodel_enroll->eagr_zipcode_est = $model_enroll->eagr_zipcode_est;
                    $newmodel_enroll->eagr_firma_par = $model_enroll->eagr_zipcode_est;
                    $newmodel_enroll->eagr_firma_dec = $model_enroll->eagr_firma_dec;
                    $newmodel_enroll->eagr_firma_est = $model_enroll->eagr_firma_est;
                    $newmodel_enroll->eagr_firma_fecha_est = $fecha_modificacion;
                    $newmodel_enroll->eagr_firma_fecha_par = $fecha_modificacion;
                    $newmodel_enroll->eagr_firma_fecha_dec = $fecha_modificacion;
                    $newmodel_enroll->eagr_estado = "1";
                    $newmodel_enroll->eagr_fecha_creacion = $fecha_modificacion;
                    $newmodel_enroll->eagr_fecha_modificacion = "";
                    $newmodel_enroll->eagr_estado_logico = "1";
                    $model_enroll->eagr_estado = '0';
                    $model_enroll->eagr_estado_logico = '0';

                    if(!$model_enroll->save()){
                        throw new Exception('Error to Save Information.');
                    }

                    if(!$newmodel_enroll->save()){
                        throw new Exception('Error to Save Information.');
                    }
                    $arrItemPrices = RegistroPagoMatricula::getItemsPrice();
                    $rep = new ExportFile();
                    $this->layout = '@modules/academico/views/registro/enrollment_main';
                    //setlocale(LC_TIME, 'es_CO.UTF-8');

                    $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
                    $rep->createReportPdf(
                            $this->render('@modules/academico/views/registro/enrollment_body', [
                                "fpag_id" => $newmodel_enroll->fpag_id,
                                "nombre_uni" => $newmodel_enroll->eagr_nombre_uni,
                                "telefono_uni" => $newmodel_enroll->eagr_telefono_uni,
                                "direccion_uni" => $newmodel_enroll->eagr_direccion_uni,
                                "apellidos_est" => $newmodel_enroll->eagr_apellidos_est,
                                "nombres_est" => $newmodel_enroll->eagr_nombres_est,
                                "direccion_est" => $newmodel_enroll->eagr_direccion_est,
                                "ciudad_est" => $newmodel_enroll->eagr_ciudad_est,
                                "estado_est" => $newmodel_enroll->eagr_estado_est,
                                "sexo_est" => $newmodel_enroll->eagr_sexo_est,
                                "ciudadania_est" => $newmodel_enroll->eagr_ciudadania_est,
                                "fnacimiento_est" => $newmodel_enroll->eagr_fnacimiento_est,
                                "lnacimiento_est" => $newmodel_enroll->eagr_lugar_nac_est,
                                "correo_est" => $newmodel_enroll->eagr_email_est,
                                "telefono_est" => $newmodel_enroll->eagr_telefono_est,
                                "programa_est" => $newmodel_enroll->eagr_programa_est,
                                "creditos_est" => $newmodel_enroll->eagr_pro_creditos_est,
                                "titulo_est" => $newmodel_enroll->eagr_pro_titulo_est,
                                "finicio_est" => $newmodel_enroll->eagr_fecha_inicio_est,
                                "fgraduacion_est" => $newmodel_enroll->eagr_fecha_graduacion_est,
                                "periodoAcad" => $newmodel_enroll->eagr_periodo_academico_est,
                                "pinteres_est" => $newmodel_enroll->eagr_porcentaje_anual_est,
                                "financiamiento" => $newmodel_enroll->eagr_financiamiento_est,
                                "costoProgCarrera_est" => $newmodel_enroll->eagr_costo_carrera_est,
                                "costoCarrera_est" => $newmodel_enroll->eagr_costo_programa_est,
                                "pagoPrograma_est" => $newmodel_enroll->eagr_pago_programa_est,
                                "cuota1" => $newmodel_enroll->eagr_primera_cuota_est,
                                "cuota2" => $newmodel_enroll->eagr_segunda_cuota_est,
                                "cuota3" => $newmodel_enroll->eagr_tercera_cuota_est,
                                "cuota4" => $newmodel_enroll->eagr_cuarta_cuota_est,
                                "cuota5" => $newmodel_enroll->eagr_quinta_cuota_est,
                                "vencimiento1" => $newmodel_enroll->eagr_primera_ven_est,
                                "vencimiento2" => $newmodel_enroll->eagr_segunda_ven_est,
                                "vencimiento3" => $newmodel_enroll->eagr_tercera_ven_est,
                                "vencimiento4" => $newmodel_enroll->eagr_cuarta_ven_est,
                                "vencimiento5" => $newmodel_enroll->eagr_quinta_ven_est,
                                "mi_est" => $newmodel_enroll->eagr_mi_est,
                                "educacion_est" => $newmodel_enroll->eagr_educacion_est,
                                "zipcode_est" => $newmodel_enroll->eagr_zipcode_est,
                                "firma_par" => $newmodel_enroll->eagr_firma_fecha_par,
                                "firma_dec" => $newmodel_enroll->eagr_firma_dec,
                                "firma_est" => $newmodel_enroll->eagr_firma_est,
                                "ffirma_par" => $newmodel_enroll->eagr_firma_fecha_par,
                                "ffirma_dec" => $newmodel_enroll->eagr_firma_fecha_dec,
                                "ffirma_est" => $newmodel_enroll->eagr_firma_fecha_est,
                                "itemsPrices" => $arrItemPrices,
                                "idPriceTuition" => [4,5,6],
                                "idPriceFees" => [1,2,3],
                            ])
                    );
                    $path = "Enrollment_Agreement_" . date("Ymdhis") . ".pdf";
                    $tmp_path = sys_get_temp_dir() . "/" . $path;

                    $rep->mpdf->Output($tmp_path, ExportFile::OUTPUT_TO_FILE);
                    
                    $body = Utilities::getMailMessage($template, array(
                                "[[user]]" => $user_names,
                                "[[nombres]]" => $modelPersona->per_pri_nombre . " " . $modelPersona->per_pri_apellido,
                                "[[dni]]" => (($modelPersona->per_cedula != "")?$modelPersona->per_cedula:$modelPersona->per_pasaporte),
                                "[[periodo]]" => $data_student["pla_periodo_academico"],
                                "[[modalidad]]" => $data_student["mod_nombre"],
                                "[[subjects]]" => $subjects
                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");
                    $titulo_mensaje = Academico::t("matriculacion", "Online Registration Cancellation");

                    $from = Yii::$app->params["adminEmail"];

                    $to = array(
                        "0" => $receiveMail,
                    );
                    $toest = $modelPersona->per_correo;
                    $files = array();
                    $asunto = Academico::t("matriculacion", "Cancellation Request Approval");
                    
                    $bodyest = Utilities::getMailMessage($templateEst, array(
                                "[[user]]" => $user_names_est,
                                "[[periodo]]" => $data_student["pla_periodo_academico"],
                                "[[modalidad]]" => $data_student["mod_nombre"],
                                "[[subjects]]" => $subjects
                                    ), Yii::$app->language, Yii::$app->basePath . "/modules/academico");

                    $transaction->commit();
                    Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);
                    $files = array(
                        "0" => $tmp_path,
                    );
                    Utilities::sendEmail($titulo_mensaje, $from, $toest, $asunto, $bodyest, $files);
                    Utilities::removeTemporalFile($tmp_path);
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Success"), false, $message);
                }else{
                    throw new Exception('Error to Save Information.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $msg = ($error)?($ex->getMessage()):(Yii::t('notificaciones', 'Your information has not been saved. Please try again.'));
                $message = array(
                    "wtmessage" => $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionStripecheckout2(){
        try{
            $payment_id = $statusMsg = ''; 
            $ordStatus  = 'error'; 
             
            // Check whether stripe token is not empty 
            if(!empty($_POST['stripeToken'])){             
                // Retrieve stripe token, card and user info from the submitted form data 
                $token  = $_POST['stripeToken']; 
                $name   = $_POST['name']; 
                $email  = $_POST['email']; 
                $valFrtsPay = $_POST['valFrtsPay'];

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
                    // Convert price to cents 
                    //$valor_inscripcion = 120;
                    $itemPriceCents = ($valFrtsPay*100); 
                     
                    // Charge a credit or a debit card 
                    try {  
                        $charge = \Stripe\Charge::create(array( 
                            'customer'    => $customer->id, 
                            'amount'      => $itemPriceCents, 
                            'currency'    => "usd", 
                            'description' => "Pago de primera cuota"
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
                                $statusMsg = 'Your Payment has been Successful!'; 
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

            return json_encode($statusMsg);
        }catch(Exception $e) {  
            return json_encode($e->getMessage());
        }
        
    }//function actionStripecheckout




    //Proceso de pago  por stripe
    public function Stripecheckout($token,$name,$email,$valFrtsPay){

            try{
            $payment_id = $statusMsg = ''; 
            $ordStatus  = 'error'; 
             
            // Check whether stripe token is not empty 
            if(!empty($token)){             
                // Retrieve stripe token, card and user info from the submitted form data 
                //$token  = $stripeToken; 
                //$name   = $name; 
                //$email  = $_POST['email']; 
                //$valFrtsPay = $_POST['valFrtsPay'];

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
                    // Convert price to cents 
                    //$valor_inscripcion = 120;
                    $itemPriceCents = ($valFrtsPay*100); 
                     
                    // Charge a credit or a debit card 
                    try {  
                        $charge = \Stripe\Charge::create(array( 
                            'customer'    => $customer->id, 
                            'amount'      => $itemPriceCents, 
                            'currency'    => "usd", 
                            'description' => "Pago de primera cuota"
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
                                //$ordStatus = 'success'; 
                                $statusMsg = 'success'; 
                                //$statusMsg = 'Your Payment has been Successful!'; 
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
            //return json_encode($statusMsg);
            return $statusMsg;
        }catch(Exception $e) {  
            //return json_encode($e->getMessage());
            return $e->getMessage();
        }

    }



    public function actionPlanual() {
    

        $mod_planual = new RegistroConfiguracion();
        $model_planual = $mod_planual->  getFechasPeriodoAcademicoActual();



 
        $mod_periodo = new PeriodoAcademico();
        $mod_planual = new RegistroConfiguracion();
       
    
        $arr_periodo = $mod_periodo->getPeriodoAcademicoActual();
        $model = $mod_planual->  getFechasPeriodoAcademicoActual();
         $dateapli1= $model['rco_fecha_ini_aplicacion'];
         $dateapli2= $model['rco_fecha_fin_aplicacion'];
         $datereg1= $model['rco_fecha_ini_registro'];
         $datereg2= $model['rco_fecha_fin_registro'];
         $dateext1= $model['rco_fecha_ini_periodoextra'];
         $dateext2= $model['rco_fecha_fin_periodoextra'];
         $datecla1= $model['rco_fecha_ini_clases'];
         $datecla2= $model['rco_fecha_fin_clases'];
         $datefin1= $model['rco_fecha_ini_examenes'];
         $datefin2= $model['rco_fecha_fin_examenes'];
     

         $mod_planual = new RegistroConfiguracion();


        
        return $this->render('planual', [
            'mod_periodo' => ArrayHelper::map(array_merge($arr_periodo), "id", "name"),
                                     'model' => $model,
                                    ]);
     }

     public function actionModificarcargacartera() {
        $perid = @Yii::$app->session->get("PB_perid");
        $usuario = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            \app\models\Utilities::putMessageLogFile('controller 1...: ');
            $total      = $data['total'];
            $tpago      = $data['tpago'];
            $ron_id    = $data['ron_id'];
            $numcuotas  = $data['numcuotas'];
            $pla_id     = $data['pla_id']?$data['pla_id']:14;
            $per_id     = base64_decode($data['per_id']);
            $con = \Yii::$app->db_facturacion;
            $con2  = \Yii::$app->db_academico;
            //$transaction = $con->beginTransaction();
            //$transaction2 = $con2->beginTransaction();

            try {
                $modelCargaCartera = new RegistroConfiguracion();
                $secuencial = $modelCargaCartera->getSecuencialCargaCartera();
                \app\models\Utilities::putMessageLogFile('controller 2...: | '. $secuencial['secuencial'].' | .....');
                $valor_cuota = $total / $numcuotas;
                $valor_cuota = number_format($valor_cuota, 2, '.', ',');  
                //if ($secuencial['secuencial'] == 0) {
                    if($tpago == 3) {
                        \app\models\Utilities::putMessageLogFile('controller 3...: ');
                        $forma_pago = 'CR';
                        $persona = new Persona();
                        $cedula = $persona->consultaPersonaId($per_id);
                        $estudiante = new Estudiante();
                        $est_id = $estudiante->getEstudiantexperid($per_id);
                        //$repetidos = $modelCargaCartera->getRegistrosDuplicados($ron_id);
                        $porcentaje = 100/$numcuotas;
                        $datosRonPes= $modelCargaCartera->getRonPes($per_id);
                        $ron_id = $datosRonPes['ron_id'];
                        $pes_id = $datosRonPes['pes_id'];
                        //\app\models\Utilities::putMessageLogFile('log 0...'.$repetidos['repetidos']);
                        //if($repetidos['repetidos']==0 ){ //If grid cuotas repetidos
                            \app\models\Utilities::putMessageLogFile('log 1...'.$est_id['est_id'].'-'.$ron_id.'-'. $secuencial['secuencial']);
                            //$registros_relacionados = $modelCargaCartera->registrarRelacionCartera($est_id['est_id'],$ron_id, $secuencial['secuencial']);
                            $registro_pago_matricula = $modelCargaCartera->registrarPagoMatricula($perid, $per_id, $pla_id, $ron_id, $total);
                            \app\models\Utilities::putMessageLogFile('RPM: '.$registro_pago_matricula.'- OK');
                            $rpm_id = $modelCargaCartera->getRpmId($per_id, $pla_id, $ron_id);
                            $rpm_id = $rpm_id['rpm_id'];
                            //$updateAdicionalMateria = $modelCargaCartera->updateAdicionalMateria($rama_id, $rpm_id);
                            \app\models\Utilities::putMessageLogFile('log 2...');
                                \app\models\Utilities::putMessageLogFile('controller N1...: '.$est_id['est_id'].'-'.$ron_id.'-'. $secuencial['secuencial']);
                            for($in = 1; $in <= $numcuotas; $in++){
                                $fechaCuotaActual = $modelCargaCartera->getCuotaActual($in);
                                $registros_cuotas = $modelCargaCartera->registrarCargaCartera($est_id['est_id'],$cedula['per_cedula'], $secuencial['secuencial'], $forma_pago,$fechaCuotaActual['fecha'],$in, $numcuotas, $valor_cuota, $total, $usuario);
                                $registro_online_cuota = $modelCargaCartera->registroOnlineCuota($ron_id, $rpm_id,$in,$fechaCuotaActual['fecha'],$porcentaje,$total);
                            }
                            
                                //$transaction->commit();
                                //$transaction2->commit();
                                $message = array(
                                    "wtmessage" => Yii::t("notificaciones", "Su información ha sido guardada con éxito."),
                                    "title" => Yii::t('jslang', 'Success'),
                                );
                                \app\models\Utilities::putMessageLogFile('controller 4...: '.$registros_cuotas);
                                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), true, $message);
                            /*} else {
                                $transaction->rollback();
                                $transaction2->rollback();
                                $message = array(
                                    "wtmessage" => Yii::t("notificaciones", "Error al grabar.".$transaction ),
                                    "title" => Yii::t('jslang', 'Error'),
                                );
                                \app\models\Utilities::putMessageLogFile('controller 5...: '.$message);
                                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                            }*/
                       /* }else{
                            \app\models\Utilities::putMessageLogFile('repetidos...: ');
                        }*/  
                    } else{  //If grid cuotas repetidos
                            \app\models\Utilities::putMessageLogFile('Tipo de pago no es Credito Directo...: ');
                            //Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }                                   
            } catch (Exception $ex) {
                \app\models\Utilities::putMessageLogFile('controller 6...: ');
                //$transaction->rollback();
                //$transaction2->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar.".$ex->getMessage()),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionSendpdf(){

        try {       
            $data = Yii::$app->request->post();            
            $per_id      = base64_decode($data['per_id']);
            $cuotas     = $data['cuotas'];
            $matriculacion_model = new Matriculacion();          
            $modelPersona = Persona::find()->where(['per_id' => $per_id])->asArray()->one();
            $modelEstudiante = Estudiante::find()->where(['per_id' => $per_id])->asArray()->one();  

            /*Cabecera*/
            $datos_planficacion = $matriculacion_model->getDataPlanStudent($per_id);    
            $pla_id = $datos_planficacion['pla_id'];
            $pes_id = $datos_planficacion['pes_id'];

            $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
            $direccion = $modelPersona['per_domicilio_cpri'];
            $matricula = $modelEstudiante['est_matricula'];

            /*Detalle de materias*/
            $matriculacion_model = new Matriculacion();
            $resp_ron_id = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $pes_id);
            $ron_id = $resp_ron_id['ron_id'];
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id);

            /*Detalles de pagos */
            $registro_pago_matricula = new RegistroPagoMatricula();
            //$resp_cant_cuota = $registro_pago_matricula->getCuotasPeriodo($rama_id);
            $cant_cuota = $cuotas;// $resp_cant_cuota['cuota'];
            $est_id = $modelEstudiante['est_id'];

            // nuevo
            /*$resp_rpm_id = $matriculacion_model->getNumeroDocumentoRegistroOnline( $ron_id, $per_id);
            $rpm_id = $resp_rpm_id['rpm_id'];*/
            $detallePagos = [];//$matriculacion_model->getDetalleCuotasRegistroOnline($ron_id, $rpm_id);
            // nuevo

            /*$resp_ccar_numero_documento = $matriculacion_model->getNumeroDocumentoRegistroOnline($rama_id);
            $ccar_numero_documento = $resp_ccar_numero_documento['cfca_numero_documento'];
            $est_id = $modelEstudiante['est_id'];
            $detallePagos = $matriculacion_model->getDetalleCuotasRegistroOnline($ccar_numero_documento, $est_id);*/

            //Valores de registro online
            $detallePagosRon = $matriculacion_model->getDetvalorRegistroOnline($ron_id);
            $ron_valor_aso_estudiante = $detallePagosRon['ron_valor_aso_estudiante'];
            $ron_valor_gastos_adm =  $detallePagosRon['ron_valor_gastos_adm']; 

            //malla academica
            $resp_maca_nombre = $matriculacion_model->getMallaAcademicaRegistroOnline($per_id);
            $maca_nombre = $resp_maca_nombre['maca_nombre'];

            $rep = new ExportFile();
            //$this->layout = false;
            $this->layout = '@modules/academico/views/tpl_registropagomatricula/main';
            setlocale(LC_TIME, 'es_CO.UTF-8');

            //$cabFact['FechaDia'] = strftime("%A %d de %B %G", strtotime(date("d-m-Y")));   
            //$this->pdf_cla_acceso = $ids;
            $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical   
            $rep->createReportPdf(
                    $this->render('@modules/academico/views/tpl_registropagomatricula/registro', [
                        'data_student' => $data_student,
                        'direccion' => $direccion,
                        'matricula' => $matricula,
                        'dataPlanificacion' => $dataPlanificacion,
                        'cant_cuota' => $cant_cuota?$cant_cuota:6,
                        'detallePagos' => $detallePagos,
                        'ron_valor_aso_estudiante' => $ron_valor_aso_estudiante,
                        'ron_valor_gastos_adm' => $ron_valor_gastos_adm,
                        'ron_id' => $ron_id,
                        'maca_nombre' => $maca_nombre,
                    ])
            );
                
                $titulo_mensaje = Academico::t('matriculacion',"Hoja Inscripcion");
                $asunto = Academico::t('matriculacion',"Envio de Hoja de Inscripcion DBE");
                //************************************************************************************* */
                $routeBase = Yii::$app->basePath . "/modules/academico/mail/layouts/messages/es/registro";
                $lang = "es";
                $content = "";
                
                $slack = ["[[user]]" => $data_student['pes_nombres'],
                "[[periodo]]" => $data_student['pla_periodo_academico'],
                "[[modalidad]]" => $data_student['mod_nombre'],];
                
                if (is_file($routeBase)){
                    $content = file_get_contents($routeBase);
                    \app\models\Utilities::putMessageLogFile('content...: '.$content);}
                    \app\models\Utilities::putMessageLogFile('route...: '.$routeBase);
                if (count($slack) > 0) {
                    foreach ($slack as $key => $value) {
                        $content = str_replace($key, $value, $content);
                    }
                }
                
                //************************************************************************************* */
                /*$body = Utilities::getMailMessage('registro', array(
                    "[[user]]" => $data_student['pes_nombres'],
                    "[[periodo]]" => '',
                    "[[modalidad]]" => '',
                    ),Yii::$app->language);*/
                $body = $content;
                
               // Yii::$app->session->set('rama_id', $rama_id);
                $ron_id      = $data['ron_id']?$data['ron_id']:1;
                $pes_id      = $data['pes_id']?$data['pes_id']:1;

                $mail_estudiante = Persona::getPerCorreo($per_id);
                $admin_email = date(Yii::$app->params["soporteEmail"]);
                \app\models\Utilities::putMessageLogFile('correo..: '.$mail_estudiante.'||'.$admin_email);

                $to = array(
                    "0" => 'analistadesarrollo01@uteg.edu.ec',
                    "1" => $mail_estudiante,
                );

            
                $path = "Registro_" . date("Ymdhis") . ".pdf";
                $tmp_path = sys_get_temp_dir() . "/" . $path;
                $rep->mpdf->Output($tmp_path, ExportFile::OUTPUT_TO_FILE);//OUTPUT_TO_STRING);//
                \app\models\Utilities::putMessageLogFile('path2..: '.$tmp_path);
                
                $files = array(
                    "0" => $tmp_path,
                );
                    
                Utilities::sendEmail($titulo_mensaje,
                Yii::$app->params["adminEmail"],  
                $to,
                $asunto, 
                $body,
                $files);

                //-----------------------------------
            


                //Utilities::sendEmail($titulo_mensaje, $from, $to, $asunto, $body, $files);

                Utilities::removeTemporalFile($tmp_path);
            
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Su información ha sido procesada con éxito."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), true, $message);
            //exit;$file = $rep->mpdf->Output('HOJAINSCRIPCION' . $ids . ".pdf", ExportFile::OUTPUT_TO_FILE);
        }
         catch (Exception $e) {
            echo $e->getMessage();
            \app\models\Utilities::putMessageLogFile('error..: '.$e->getMessage());
        }

           

                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information was successfully saved.'),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                //-----------------------------------
            return;
    }

    public function actionInscripcionpdf($ids) {
        try {
            $ids = $_GET['ids'];
            $per_id = $ids;
            $rama_id  = $_GET['rama_id'];
            $matriculacion_model = new Matriculacion();          
            $modelPersona = Persona::find()->where(['per_id' => $per_id])->asArray()->one();
            $modelEstudiante = Estudiante::find()->where(['per_id' => $per_id])->asArray()->one();
           
            /*Cabecera*/
            $datos_planficacion = $matriculacion_model->getDataPlanStudent($per_id);    
            $pla_id = $datos_planficacion['pla_id'];
            $pes_id = $datos_planficacion['pes_id'];

            $data_student = $matriculacion_model->getDataStudent($per_id, $pla_id, $pes_id);
            $direccion = $modelPersona['per_domicilio_cpri'];
            $matricula = $modelEstudiante['est_matricula'];

            /*Detalle de materias*/
            $matriculacion_model = new Matriculacion();
            $resp_ron_id = $matriculacion_model->getDataStudenFromRegistroOnline($per_id, $pes_id);
            $ron_id = $resp_ron_id['ron_id'];
            $dataPlanificacion = $matriculacion_model->getPlanificationFromRegistroOnline($ron_id);

            /*Detalles de pagos */
            $registro_pago_matricula = new RegistroPagoMatricula();
            $resp_cant_cuota = $registro_pago_matricula->getCuotasPeriodo($rama_id);
            $cant_cuota = $resp_cant_cuota['cuota'];
            $est_id = $modelEstudiante['est_id'];

            // nuevo
            $resp_rpm_id = $matriculacion_model->getNumeroDocumentoRegistroOnline($rama_id, $ron_id, $per_id);
            $rpm_id = $resp_rpm_id['rpm_id'];
            $detallePagos = $matriculacion_model->getDetalleCuotasRegistroOnline($ron_id, $rpm_id);
            // nuevo

            /*$resp_ccar_numero_documento = $matriculacion_model->getNumeroDocumentoRegistroOnline($rama_id);
            $ccar_numero_documento = $resp_ccar_numero_documento['cfca_numero_documento'];
            $est_id = $modelEstudiante['est_id'];
            $detallePagos = $matriculacion_model->getDetalleCuotasRegistroOnline($ccar_numero_documento, $est_id);*/

            //Valores de registro online
            $detallePagosRon = $matriculacion_model->getDetvalorRegistroOnline($ron_id);
            $ron_valor_aso_estudiante = $detallePagosRon['ron_valor_aso_estudiante'];
            $ron_valor_gastos_adm =  $detallePagosRon['ron_valor_gastos_adm']; 

            //malla academica
            $resp_maca_nombre = $matriculacion_model->getMallaAcademicaRegistroOnline($per_id);
            $maca_nombre = $resp_maca_nombre['maca_nombre'];

            $rep = new ExportFile();
            //$this->layout = false;
            $this->layout = '@modules/academico/views/tpl_registropagomatricula/main';
            setlocale(LC_TIME, 'es_CO.UTF-8');

            //$cabFact['FechaDia'] = strftime("%A %d de %B %G", strtotime(date("d-m-Y")));   
            //$this->pdf_cla_acceso = $ids;
            $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical   
            $rep->createReportPdf(
                    $this->render('@modules/academico/views/tpl_registropagomatricula/registro', [
                        'data_student' => $data_student,
                        'direccion' => $direccion,
                        'matricula' => $matricula,
                        'dataPlanificacion' => $dataPlanificacion,
                        'cant_cuota' => $cant_cuota?$cant_cuota:6,
                        'detallePagos' => $detallePagos,
                        'ron_valor_aso_estudiante' => $ron_valor_aso_estudiante,
                        'ron_valor_gastos_adm' => $ron_valor_gastos_adm,
                        'ron_id' => $ron_id,
                        'maca_nombre' => $maca_nombre,
                    ])
            );
            $rep->mpdf->Output('HOJAINSCRIPCION' . $ids . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
            //exit;
        }
         catch (Exception $e) {
            echo $e->getMessage();
        }
    
    }
}
