<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\Estudiante;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\DistributivoAcademicoEstudiante;
use app\modules\academico\models\DistributivoAcademicoHorario;
use app\modules\academico\models\SemestreAcademico;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\TipoDistributivo;
use app\modules\academico\models\PromocionPrograma;
use app\modules\academico\models\ParaleloPromocionPrograma;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\models\ExportFile;
use app\models\Persona;
use app\modules\academico\models\Profesor;
use Exception;
use yii\data\ArrayDataProvider;

academico::registerTranslations();
admision::registerTranslations();

class DistributivoestudianteController extends \app\components\CController {

    public function actionIndex($id) {
        $per_id = @Yii::$app->session->get("PB_perid");
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $distributivoEst_model = new DistributivoAcademicoEstudiante();
        if(!isset($id) && $id <= 0){
            return $this->redirect('distributivoacademico/index');
        }
        
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $search = $data['search'];
            $id = $data['id'];
            $model = $distributivoEst_model->getListadoDistributivoEstudiante($id, $search);
            return $this->render('index-grid', [
                        "model" => $model,
            ]);
        }
        
        $distributivo_model = DistributivoAcademico::findOne($id);
        $distributivo_hora = DistributivoAcademicoHorario::findOne($distributivo_model->daho_id);
        $mod_modalidad = Modalidad::findOne($distributivo_hora->mod_id);
        $mod_unidad = UnidadAcademica::findOne($distributivo_hora->uaca_id);
        $mod_asignatura = Asignatura::findOne($distributivo_model->asi_id);
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademico($distributivo_model->paca_id);
        $mod_profesor = Profesor::findOne($distributivo_model->pro_id);
        $mod_persona = Persona::findOne($mod_profesor->per_id);
        $arr_jornada = array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia");
        $model = $distributivoEst_model->getListadoDistributivoEstudiante($id);

        return $this->render('index', [
                    'unidad' => $mod_unidad->uaca_nombre,
                    'profesor' => $mod_persona->per_pri_nombre . " " . $mod_persona->per_pri_apellido,
                    'modalidad' => $mod_modalidad->mod_nombre,
                    'periodo' => $periodo[0]['name'],
                    'materia' => $mod_asignatura->asi_nombre,
                    'horario' => $distributivo_hora->daho_horario,
                    'model' => $model,
                    'jornada' => $arr_jornada[$distributivo_hora->daho_jornada],
                    'daca_id' => $id,
        ]);
    }

    public function actionEdit($id) {
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $distributivoEst_model = new DistributivoAcademicoEstudiante();
        if(!isset($id) && $id <= 0){
            return $this->redirect('distributivoacademico/index');
        }
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $search = $data['search'];
            $id = $data['id'];
            $model = $distributivoEst_model->getListadoDistributivoEstudiante($id, $search);
            return $this->render('edit-grid', [
                        "model" => $model,
            ]);
        }
        
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data['PBgetAutoComplete']) {
                $search = $data['search'];
                $unidad = $data['unidad'];
                $response = $distributivoEst_model->getEstudiantesXUnidadAcademica($unidad, $search);
                return json_encode($response);
            }
            if($data['PBgetDataEstudiante']){
                $est_id = $data['est_id'];
                $mod_est = Estudiante::findOne($est_id);
                $mod_per = Persona::findOne($mod_est->per_id);
                $arr_carrera = $mod_est->getInfoCarreraEstudiante($est_id, $emp_id);
                $data = [
                    "nombres" => $mod_per->per_pri_nombre . " " . $mod_per->per_seg_nombre,
                    "apellidos" => $mod_per->per_pri_apellido . " " . $mod_per->per_seg_apellido,
                    "matricula" => $mod_est->est_matricula,
                    "carrera" => $arr_carrera['Carrera'],
                ];
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', [], $data);
            }
        }
        $distributivo_model = DistributivoAcademico::findOne($id);
        $distributivo_hora = DistributivoAcademicoHorario::findOne($distributivo_model->daho_id);
        $mod_modalidad = Modalidad::findOne($distributivo_hora->mod_id);
        $mod_unidad = UnidadAcademica::findOne($distributivo_hora->uaca_id);
        $mod_asignatura = Asignatura::findOne($distributivo_model->asi_id);
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademico($distributivo_model->paca_id);
        $mod_profesor = Profesor::findOne($distributivo_model->pro_id);
        $mod_persona = Persona::findOne($mod_profesor->per_id);
        $arr_jornada = array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia");
        $model = $distributivoEst_model->getListadoDistributivoEstudiante($id);

        return $this->render('edit', [
                    'unidad' => $mod_unidad->uaca_nombre,
                    'profesor' => $mod_persona->per_pri_nombre . " " . $mod_persona->per_pri_apellido,
                    'modalidad' => $mod_modalidad->mod_nombre,
                    'periodo' => $periodo[0]['name'],
                    'materia' => $mod_asignatura->asi_nombre,
                    'horario' => $distributivo_hora->daho_horario,
                    'model' => $model,
                    'jornada' => $arr_jornada[$distributivo_hora->daho_jornada],
                    'daca_id' => $id,
                    'uaca_id' => $distributivo_hora->uaca_id,
        ]);
    }

    public function actionSave(){
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        $distributivoEst_model = new DistributivoAcademicoEstudiante();
        try{
            if (Yii::$app->request->isAjax) {
                $data = Yii::$app->request->post();
                $daca_id = $data['id'];
                $est_id = $data['est_id'];
                $dataExists = DistributivoAcademicoEstudiante::findOne(['daca_id' => $daca_id, 'est_id' => $est_id, 'daes_estado' => '1', 'daes_estado_logico' => '1']);
                if(isset($dataExists) && $dataExists != ""){
                    $message = array(
                        "wtmessage" => academico::t('distributivoacademico', 'Register already exists in System.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
                $distributivoEst_model->daca_id = $daca_id;
                $distributivoEst_model->est_id = $est_id;
                $distributivoEst_model->daes_fecha_registro = $fecha_transaccion;
                $distributivoEst_model->daes_estado = '1';
                $distributivoEst_model->daes_estado_logico = '1';
                if ($distributivoEst_model->save()) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error en Guardar registro.');
                }
            }
        }catch(Exception $e){
            $message = array(
                "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                "title" => Yii::t('jslang', 'Error'),
            );
            return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
        }
    }

    public function actionDelete(){
        if (Yii::$app->request->isAjax) {
            $usu_id = @Yii::$app->session->get("PB_iduser");
            $data = Yii::$app->request->post();
            $fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
            try {
                $id = $data["id"];
                $model = DistributivoAcademicoEstudiante::findOne($id);
                $model->daes_fecha_modificacion = $fecha_transaccion;
                $model->daes_estado = '0';
                $model->daes_estado_logico = '0';
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
        $colPosition = array("C", "D", "E", "F", "G", "H");
        $arrHeader = array(
            academico::t("matriculacion", "Student"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Email"),
            Yii::t("formulario", "Phone"),
            academico::t("matriculacion", "Registration Number"),
            academico::t("matriculacion", "Career"),
        );
        $distributivo_model = new DistributivoAcademicoEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = ($data['search'] != "")?$data['search']:NULL;
        $arrSearch["id"] = ($data['id'] > 0)?$data['id']:NULL;

        $arrData = $distributivo_model->getListadoDistributivoEstudiante($arrSearch["id"], $arrSearch["search"], true);
        foreach($arrData as $key => $value){
            unset($arrData[$key]["Id"]);
        }
        $nameReport = academico::t("distributivoacademico", "Student Lists by Subject");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExportpdf() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("distributivoacademico", "Student Lists by Subject"); // Titulo del reporte
        $arrHeader = array(
            academico::t("matriculacion", "Student"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Email"),
            Yii::t("formulario", "Phone"),
            academico::t("matriculacion", "Registration Number"),
            academico::t("matriculacion", "Career"),
        );
        $distributivo_model = new DistributivoAcademicoEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = ($data['search'] != "")?$data['search']:NULL;
        $arrSearch["id"] = ($data['id'] > 0)?$data['id']:NULL;

        $arrData = $distributivo_model->getListadoDistributivoEstudiante($arrSearch["id"], $arrSearch["search"], true);
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
    public function actionHorarioestudiante() { 
        $per_id = @Yii::$app->session->get("PB_perid");           
        $mod_distributivoest = new DistributivoAcademicoEstudiante(); 
        $mod_estudiante = new Estudiante();
        // Obtener est_id a partir del per_id
        $arr_estudiante = $mod_estudiante->getEstudiantexperid($per_id);
        $arr_horario = $mod_distributivoest->consultarHorarioEstudiante($arr_estudiante["est_id"]);      
        return $this->render('horarioestudiante', [
                    'arr_horario' => $arr_horario,                   
        ]);
    } 
}