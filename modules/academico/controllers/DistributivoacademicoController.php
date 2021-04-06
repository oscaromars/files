<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\DistributivoAcademicoHorario;
use app\modules\academico\models\SemestreAcademico;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\TipoDistributivo;
use app\modules\academico\models\PromocionPrograma;
use app\modules\academico\models\ParaleloPromocionPrograma;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\DistributivoCabecera;
use app\modules\academico\models\Planificacion;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\models\ExportFile;
use app\modules\academico\models\Profesor;
use app\modules\academico\models\PeriodoAcademico;
use Exception;

academico::registerTranslations();
admision::registerTranslations();

class DistributivoacademicoController extends \app\components\CController {        
    public function paralelo() {
        return [
            '0' => Yii::t("formulario", "Seleccionar"),
            '1' => Yii::t("formulario", "Paralelo 1"),
            '2' => Yii::t("formulario", "Paralelo 2"),
            '3' => Yii::t("formulario", "Paralelo 3"),            
            '4' => Yii::t("formulario", "Paralelo 4"),    
        ];
    }
    
    private function estados() {
        return [
            '0' => Yii::t("formulario", "Seleccionar"),
            '1' => Yii::t("formulario", "Por aprobar"),
            '2' => Yii::t("formulario", "Aprobado"),
            '3' => Yii::t("formulario", "No aprobado"),      
        ];
    }
    
    public function actionIndex() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $model = NULL;
        $distributivo_model = new DistributivoAcademico();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $search = $data['search'];
            $unidad = (isset($data['unidad']) && $data['unidad'] > 0)?$data['unidad']:NULL;
            $modalidad = (isset($data['modalidad']) && $data['modalidad'] > 0)?$data['modalidad']:NULL;
            $periodo = (isset($data['periodo']) && $data['periodo'] > 0)?$data['periodo']:NULL;
            $materia = (isset($data['materia']) && $data['materia'] > 0)?$data['materia']:NULL;
            $jornada = (isset($data['jornada']) && $data['jornada'] > 0)?$data['jornada']:NULL;
            $model = $distributivo_model->getListadoDistributivo($search, $modalidad, $materia, $jornada, $unidad, $periodo);
            return $this->render('index-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $distributivo_model->getListadoDistributivo();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getjornada"])){
                $jornada = $distributivo_model->getJornadasByUnidadAcad($data["uaca_id"], $data["mod_id"]);
                $message = array("jornada" => $jornada);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["gethorario"])){
                $horario = $distributivo_model->getHorariosByUnidadAcad($data["uaca_id"], $data["mod_id"], $data['jornada_id']);
                $message = array("horario" => $horario);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $mod_asignatura = Asignatura::findAll(['asi_estado' => 1, 'asi_estado_logico' => 1]);
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        $arr_jornada = $distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);
        $arr_periodo = $mod_periodo->consultarPeriodoAcademico();
        return $this->render('index', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'mod_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
                    'mod_materias' => ArrayHelper::map(array_merge([["asi_id" => "0", "asi_nombre" => Yii::t("formulario", "Grid")]], $mod_asignatura), "asi_id", "asi_nombre"),
                    'model' => $model,
                    'mod_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_jornada), "id", "name"),
        ]);
    }

    public function actionNew() {
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new Planificacion();
        $mod_asignatura = new Asignatura();
        $mod_profesor = new Profesor();
        $distributivo_model = new DistributivoAcademico();
        $mod_periodoActual = new PeriodoAcademico();
        $mod_tipo_distributivo = new TipoDistributivo();
        $arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual();
        $mod_horario = new DistributivoAcademicoHorario();
        
        if (Yii::$app->request->isAjax) {            
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getperiodo"])){
                $periodo = $mod_periodo->getPeriodos_x_modalidad($data["mod_id"]);
                $message = array("periodo" => $periodo);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getjornada"])){                
                $jornada = $distributivo_model->getJornadasByUnidadAcad($data["uaca_id"], $data["mod_id"]);
                $message = array("jornada" => $jornada);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["gethorario"])){
                $horario = $distributivo_model->getHorariosByUnidadAcad($data["uaca_id"], $data["mod_id"], $data['jornada_id']);
                $message = array("horario" => $horario);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getasignatura"])){               
                $asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif($data["periodo_id"], $data["jornada_id"], $arr_periodoActual["baca_nombre"]);
                $message = array("asignatura" => $asignatura);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getasignaturapos"])){               
                $asignatura = $mod_asignatura->getAsignaturaPosgrado($data["meun_id"]);
                $message = array("asignaturapos" => $asignatura);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            
            if(isset($data["getestudio"])){               
                $estudio = $distributivo_model->getModalidadEstudio($data["uaca_id"], $data["mod_id"]);
                $message = array("carrera" => $estudio);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }

            if(isset($data["getparalelo"])){                      
                $paralelos = $mod_horario->consultarParaleloHorario($data["daho_id"]);
                $message = array("paralelo" => $paralelos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }


        }
                
        $arr_profesor = $mod_profesor->getProfesores();        
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);        
        $arr_periodo = $mod_periodo->getPeriodos_x_modalidad($arr_modalidad[0]["id"]);
        $arr_jornada = $distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);           
        $arr_asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif($arr_periodo[0]["id"],"N", $arr_periodoActual["baca_nombre"]);
        $arr_horario = $distributivo_model->getHorariosByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"], $arr_jornada[0]["id"]);
        $model = $distributivo_model->getDistribAcadXprofesorXperiodo(0,0);
        $arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo();
        $arr_programa = $distributivo_model->getModalidadEstudio(2, 1);
                
        return $this->render('new', [
            'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
            'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodo), "id", "name"),
            'arr_materias' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
            'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_jornada), "id", "name"),
            'arr_horario' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_horario), "id", "name"),
            'arr_tipo_asignacion' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_tipo_distributivo), "id", "name"),
            'arr_paralelo' => $this->paralelo(),
            'model' => $model,
            'arr_periodoActual' => $arr_periodoActual,
            'arr_programa' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_programa), "id", "name"),
        ]);
    }

    public function actionTest() {
        
        $searchModel = new \app\modules\academico\models\DistributivoAcademicoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('test', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
          
    }
    
  public function actionSave() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $distributivo_model = new DistributivoAcademico();
        $distributivo_cab = new DistributivoCabecera();
        \app\models\Utilities::putMessageLogFile('existe validacion ');      
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $pro_id = $data['profesor'];
                $paca_id = $data['periodo'];                
                $dts = (isset($data["grid_docencia"]) && $data["grid_docencia"] != "") ? $data["grid_docencia"] : NULL;     
                $datos = json_decode($dts);
                //Validar que no exista en distributivo_cabecera porque para crear no debe existir.
                $cons = $distributivo_cab->existeDistCabecera($paca_id,$pro_id);                                            
                if ($cons==0) {       
                    \app\models\Utilities::putMessageLogFile('existe validacion 1' . $dts);                 
                    if (isset($datos)) {                    
                        $valida = 1;                                   
                        for ($i = 0; $i < sizeof($datos); $i++) {                             
                            // Valida que no exista el mismo tipo de distributivo en otro profesor.
                            if (($datos[$i]->tasi_id == 1) || ($datos[$i]->tasi_id == 7)) {                                                                             
                                $dataExisOtro = $distributivo_model->existsDistribAcadOtroProf($datos[$i]->uni_id, $datos[$i]->tasi_id, $datos[$i]->asi_id, $paca_id, $datos[$i]->hor_id, $datos[$i]->par_id);
                                
                                
                                if(!empty($dataExisOtro)) { 
                                    \app\models\Utilities::putMessageLogFile('existe validacion 2');
                                    $valida = 0;
                                    $transaction->rollback();
                                    $message = array(
                                        "wtmessage" => academico::t('distributivoacademico', 'Ya se encuentra asignada la materia '. $dataExisOtro["asignatura"] . ' en el docente ' . $dataExisOtro["profesor"] . " en el mismo horario y paralelo."),
                                        "title" => Yii::t('jslang', 'Error'),
                                    );
                                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                                }
                            }                                
                        }
                    }                  
                    //ha cumplido todas las validaciones entonces graba.
                    if ($valida == 1) {                                             
                        $cab = $distributivo_cab->insertarDistributivoCab($paca_id, $pro_id); 
                         
                        if ($cab>0) {                                
                            for ($i = 0; $i < sizeof($datos); $i++) {
                                // Grabar en distributivo académico
                               // \app\models\Utilities::putMessageLogFile('existe validacion 3' . $datos[$i]['tdis_id']);
                                  
                                $res= $distributivo_model->insertarDistributivoAcademico($i, $datos, $pro_id, $paca_id);                             
                                if ($res>0) {                                     
                                    $exito = '1';
                                } else {
                                    $exito = '0';
                                }                               
                            }   
                        }
                    }                
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => academico::t('distributivoacademico', 'Ya existe distributivo para este profesor en el período actual'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }            
                if ($exito=='1') {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Su información no ha sido grabada. Por favor intente nuevamente o contacte al área de DesarrolloXXX.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                   
            }catch(Exception $e){
                \app\models\Utilities::putMessageLogFile('error: '. $e);
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Su información no ha sido grabada. Por favor intente nuevamente o contacte al área de Desarrollo.'.$m),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionActualizar() {
        \app\models\Utilities::putMessageLogFile('entra: ');
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $distributivo_model = new DistributivoAcademico();
        $distributivo_cab = new DistributivoCabecera();
        
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
           
            try {
                $pro_id = $data['profesor'];
                $paca_id = $data['periodo'];
                $pcab_id = $data['id'];             
                $dts = (isset($data["grid_docencia"]) && $data["grid_docencia"] != "") ? $data["grid_docencia"] : NULL;     
                $datos = json_decode($dts);
                \app\models\Utilities::putMessageLogFile($pcab_id);
                //Validar que no exista en distributivo_cabecera porque para crear no debe existir.
                //$cons = $distributivo_cab->existeDistCabecera($paca_id,$pro_id);       

                $cons = $distributivo_cab->EliminaexisteDistCabecera($pcab_id,$pro_id);   
                if ($cons) {                            
                    $ok='1';
                } else {                            
                    $ok='0';
                }
                if ($ok==1) {       
                    if (isset($datos)) {                    
                        $valida = 1;                                   
                        for ($i = 0; $i < sizeof($datos); $i++) {                             
                            // Valida que no exista el mismo tipo de distributivo en otro profesor.
                            if ($datos[$i]->tasi_id == 1) {                                                                             
                                
                                $dataExisOtro = $distributivo_model->existsDistribAcadOtroProf($datos[$i]->uni_id, $datos[$i]->tasi_id, $datos[$i]->asi_id, $paca_id, $datos[$i]->hor_id, $datos[$i]->par_id);
                                
                                
                                if(!empty($dataExisOtro)) { 
                                    $valida = 0;
                                    $transaction->rollback();
                                    $message = array(
                                        "wtmessage" => academico::t('distributivoacademico', 'Ya se encuentra asignada la materia '. $dataExisOtro["asignatura"] . ' en el docente ' . $dataExisOtro["profesor"] . " en el mismo horario y paralelo."),
                                        "title" => Yii::t('jslang', 'Error'),
                                    );
                                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                                }
                            }                                
                        }
                    }                  
                    //ha cumplido todas las validaciones entonces graba.
                    if ($valida == 1) {                                             
                        $cab = $distributivo_cab->insertarDistributivoCab($paca_id, $pro_id); 
                         
                        if ($cab>0) {                                
                            for ($i = 0; $i < sizeof($datos); $i++) {
                                // Grabar en distributivo académico
                                
                                $res= $distributivo_model->insertarDistributivoAcademico($i, $datos, $pro_id, $paca_id);                             
                                if ($res>0) {                                     
                                    $exito = '1';
                                } else {
                                    $exito = '0';
                                }                               
                            }   
                        }
                    }                
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => academico::t('distributivoacademico', 'Ya existe distributivo para este profesor en el período actual'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }            
                if ($exito=='1') {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Su información no ha sido grabada. Por favor intente nuevamente o contacte al área de DesarrolloXXX.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                   
            }catch(Exception $e){
                \app\models\Utilities::putMessageLogFile('error: '. $e);
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Su información no ha sido grabada. Por favor intente nuevamente o contacte al área de Desarrollo.'.$m),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    
    public function actionView($id) {     
        $emp_id = @Yii::$app->session->get("PB_idempresa");           
        $distributivo_model = new DistributivoAcademico();
        $distributivo_cab = new DistributivoCabecera();
        $mod_periodoActual = new PeriodoAcademico();
        $mod_profesor = new Profesor();
        $mod_unidad = new UnidadAcademica();
        $mod_modalidad = new Modalidad();
        $mod_periodo = new Planificacion();
        $mod_asignatura = new Asignatura();
        $mod_tipo_distributivo = new TipoDistributivo();

        $resCab = $distributivo_cab->obtenerDatosCabecera($id);
       
        $arr_distributivo = $distributivo_model->getListarDistribProfesor($resCab["paca_id"], $resCab["pro_id"]);

        $arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual();
        $arr_profesor = $mod_profesor->getProfesores();        
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);        
        $arr_periodo = $mod_periodo->getPeriodos_x_modalidad($arr_modalidad[0]["id"]);
        $arr_jornada = $distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);           
        $arr_asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif($arr_periodo[0]["id"],"N", $arr_periodoActual["baca_nombre"]);
        $arr_horario = $distributivo_model->getHorariosByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"], $arr_jornada[0]["id"]);        
        $arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo();        
        $arr_programa = $distributivo_model->getModalidadEstudio(2, 1);

        return $this->render('view', [
            'arr_cabecera' => $resCab,            
            'arr_detalle' => $arr_distributivo,   
            'arr_estados' => $this->estados(),
            'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
            'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodo), "id", "name"),
            'arr_materias' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
            'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_jornada), "id", "name"),
            'arr_horario' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_horario), "id", "name"),
            'arr_tipo_asignacion' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_tipo_distributivo), "id", "name"),
            'arr_paralelo' => $this->paralelo(),            
            'arr_periodoActual' => $arr_periodoActual,
            'arr_programa' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_programa), "id", "name"),       
        ]);
    }

  
    public function actionEditcab($id) {     
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new Planificacion();
        $mod_asignatura = new Asignatura();
        $mod_profesor = new Profesor();        
        $distributivo_cab = new DistributivoCabecera();
        $distributivo_model = new DistributivoAcademico();
        $mod_periodoActual = new PeriodoAcademico();
        $mod_tipo_distributivo = new TipoDistributivo();
        $arr_periodoActual = $mod_periodoActual->getPeriodoAcademicoActual();
        
        if (Yii::$app->request->isAjax) {            
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getperiodo"])){
                $periodo = $mod_periodo->getPeriodos_x_modalidad($data["mod_id"]);
                $message = array("periodo" => $periodo);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getjornada"])){                
                $jornada = $distributivo_model->getJornadasByUnidadAcad($data["uaca_id"], $data["mod_id"]);
                $message = array("jornada" => $jornada);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["gethorario"])){
                $horario = $distributivo_model->getHorariosByUnidadAcad($data["uaca_id"], $data["mod_id"], $data['jornada_id']);
                $message = array("horario" => $horario);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getasignatura"])){               
                $asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif($data["periodo_id"], $data["jornada_id"], $arr_periodoActual["baca_nombre"]);
                $message = array("asignatura" => $asignatura);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if(isset($data["getasignaturapos"])){               
                $asignatura = $mod_asignatura->getAsignaturaPosgrado($data["meun_id"]);
                $message = array("asignaturapos" => $asignatura);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            
            if(isset($data["getestudio"])){               
                $estudio = $distributivo_model->getModalidadEstudio($data["uaca_id"], $data["mod_id"]);
                $message = array("carrera" => $estudio);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
                
        $resCab = $distributivo_cab->obtenerDatosCabecera($id);
        $arr_distributivo = $distributivo_model->getListarDistribProfesor($resCab["paca_id"], $resCab["pro_id"]);
        $arr_profesor = $mod_profesor->getProfesores();        
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], $emp_id);        
        $arr_periodo = $mod_periodo->getPeriodos_x_modalidad($arr_modalidad[0]["id"]);
        $arr_jornada = $distributivo_model->getJornadasByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"]);           
        $arr_asignatura = $mod_asignatura->getAsignatura_x_bloque_x_planif($arr_periodo[0]["id"],"N", $arr_periodoActual["baca_nombre"]);
        $arr_horario = $distributivo_model->getHorariosByUnidadAcad($arr_unidad[0]["id"], $arr_modalidad[0]["id"], $arr_jornada[0]["id"]);
        $model = $distributivo_model->getDistribAcadXprofesorXperiodo(0,0);
        $arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo();
        $arr_programa = $distributivo_model->getModalidadEstudio(2, 1);
                
        return $this->render('editcab', [
            'arr_cabecera' => $resCab,            
            'arr_detalle' => $arr_distributivo,   
            'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
            'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
            'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodo), "id", "name"),
            'arr_materias' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
            'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_jornada), "id", "name"),
            'arr_horario' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_horario), "id", "name"),
            'arr_tipo_asignacion' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_tipo_distributivo), "id", "name"),
            'arr_paralelo' => $this->paralelo(),
            'model' => $model,
            'arr_periodoActual' => $arr_periodoActual,
            'arr_programa' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_programa), "id", "name"),
        ]);
    }
    
    public function actionEdit($id) {
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $distributivo_model = DistributivoAcademico::findOne($id);
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad = $mod_modalidad->consultarModalidad($distributivo_model->uaca_id, $emp_id);
        $arr_periodo = $mod_periodo->consultarPeriodoAcademico();
        $mod_asignatura = Asignatura::findAll(['asi_estado' => 1, 'asi_estado_logico' => 1]);
        $arr_jornada = $distributivo_model->getJornadasByUnidadAcad($distributivo_model->uaca_id, $distributivo_model->mod_id);
        $mod_profesor = new Profesor();
        $arr_profesor = $mod_profesor->getProfesores();
        $arr_horario = $distributivo_model->getHorariosByUnidadAcad($distributivo_model->uaca_id, $distributivo_model->mod_id, $arr_jornada[0]["id"]);
        $mod_horario = DistributivoAcademicoHorario::findOne($distributivo_model->daho_id);
        $horario_values = ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_horario), "id", "name");
        return $this->render('edit', [
            'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Grid")]], $arr_profesor), "Id", "Nombres"),
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
            'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
            'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
            'arr_materias' => ArrayHelper::map(array_merge([["asi_id" => "0", "asi_nombre" => Yii::t("formulario", "Grid")]], $mod_asignatura), "asi_id", "asi_nombre"),
            'arr_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_jornada), "id", "name"),
            'arr_horario' => $horario_values,
            'pro_id'  => $distributivo_model->pro_id,
            'uaca_id' => $distributivo_model->uaca_id,
            'mod_id'  => $distributivo_model->mod_id,
            'paca_id' => $distributivo_model->paca_id,
            'asi_id'  => $distributivo_model->asi_id,
            'horario' => array_search($mod_horario->daho_horario, $horario_values),
            'jornada' => $mod_horario->daho_jornada,
            'daca_id' => $id,
        ]);
    }
  
    public function actionUpdate() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            try {
                $daca_id = $data['id'];
                $pro_id = $data['profesor'];
                $uaca_id = $data['unidad'];
                $mod_id = $data['modalidad'];
                $paca_id = $data['periodo'];
                $jornada_id = $data['jornada'];
                $horario = $data['horario'];
                $materia = $data['materia'];
                $distributivo_model = DistributivoAcademico::findOne($daca_id);
                $dataExists = $distributivo_model->existsDistribucionAcademico($pro_id, $materia, $uaca_id, $mod_id, $paca_id, $jornada_id, $horario);
                if(isset($dataExists) && $dataExists != "" && count($dataExists) > 0){
                    if($dataExists['id'] == $daca_id){
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }
                    $message = array(
                        "wtmessage" => academico::t('distributivoacademico', 'Register already exists in System.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                $dataHorario = $distributivo_model->getDistribucionAcademicoHorario($uaca_id, $mod_id, $jornada_id, $horario);

                $distributivo_model->pro_id = $pro_id;
                $distributivo_model->mod_id = $mod_id;
                $distributivo_model->asi_id = $materia;
                $distributivo_model->paca_id = $paca_id;
                $distributivo_model->daho_id = $dataHorario['daho_id'];    
                $distributivo_model->daca_fecha_modificacion = $fecha_transaccion;
                $distributivo_model->daca_usuario_modifica = $usu_id;
                if ($distributivo_model->update() !== false) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error en Guardar registro.');
                }
            }catch(Exception $e){
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $usu_id = @Yii::$app->session->get("PB_iduser");
            $data = Yii::$app->request->post();
            $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
            try {
                $id = $data["id"];
                $model = DistributivoAcademico::findOne($id);
                $model->daca_fecha_modificacion = $fecha_transaccion;
                $model->daca_usuario_modifica = $usu_id;
                $model->daca_estado = '0';
                $model->daca_estado_logico = '0';
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error SubModulo no ha sido eliminado.');
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

    
    
    public function actionExportexcel() {
        $per_id = @Yii::$app->session->get("PB_perid");
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I");
        $arrHeader = array(
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            academico::t("Academico", "Working day"),
        );
        $distributivo_model = new DistributivoAcademico();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = ($data['search'] != "")?$data['search']:NULL;
        $arrSearch["unidad"] = ($data['unidad'] > 0)?$data['unidad']:NULL;
        $arrSearch["modalidad"] = ($data['modalidad'] > 0)?$data['modalidad']:NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0)?$data['periodo']:NULL;
        $arrSearch["asignatura"] = ($data['asignatura'] > 0)?$data['asignatura']:NULL;
        $arrSearch["jornada"] = ($data['jornada'] > 0)?$data['jornada']:NULL;

        $arrData = $distributivo_model->getListadoDistributivo($arrSearch["search"], $arrSearch["modalidad"], $arrSearch["asignatura"], $arrSearch["jornada"], $arrSearch["unidad"], $arrSearch["periodo"], true);
        foreach($arrData as $key => $value){
            unset($arrData[$key]["Id"]);
        }
        $nameReport = academico::t("distributivoacademico", "Profesor Lists by Subject");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExportpdf() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("distributivoacademico", "Profesor Lists by Subject"); // Titulo del reporte
        $arrHeader = array(
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            academico::t("Academico", "Working day"),
        );
        $distributivo_model = new DistributivoAcademico();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = ($data['search'] != "")?$data['search']:NULL;
        $arrSearch["unidad"] = ($data['unidad'] > 0)?$data['unidad']:NULL;
        $arrSearch["modalidad"] = ($data['modalidad'] > 0)?$data['modalidad']:NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0)?$data['periodo']:NULL;
        $arrSearch["asignatura"] = ($data['asignatura'] > 0)?$data['asignatura']:NULL;
        $arrSearch["jornada"] = ($data['jornada'] > 0)?$data['jornada']:NULL;

        $arrData = $distributivo_model->getListadoDistributivo($arrSearch["search"], $arrSearch["modalidad"], $arrSearch["asignatura"], $arrSearch["jornada"], $arrSearch["unidad"], $arrSearch["periodo"], true);
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }    
}
