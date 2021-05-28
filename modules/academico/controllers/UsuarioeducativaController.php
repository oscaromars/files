<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\Persona;
use app\models\Usuario;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use yii\base\Exception;
use app\modules\academico\models\UsuarioEducativa;
use app\modules\academico\models\CursoEducativa;
use app\modules\academico\models\CursoEducativaUnidad;
use app\modules\academico\models\CursoEducativaEstudiante;
use app\modules\academico\models\CursoEducativaDistributivo;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\Distributivo;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\Profesor;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\DistributivoAcademicoHorario;
use app\modules\academico\models\SemestreAcademico;
use app\modules\academico\models\TipoDistributivo;
use app\modules\academico\models\PromocionPrograma;
use app\modules\academico\models\ParaleloPromocionPrograma;
use app\modules\academico\models\Planificacion;
use app\models\ExportFile;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

class UsuarioeducativaController extends \app\components\CController {

    public function actionIndex() { 
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_asignatura = new Asignatura(); 
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];  
            $arrSearch["periodo"] = $data['periodo'];
            $arrSearch["asignatura"] = $data['asignatura'];                     
            $model = $mod_educativa->consultarCursoEducativa($arrSearch, 1, 1);
            return $this->render('index-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $mod_educativa->consultarCursoEducativa(null, 1, 1);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            $arr_asignatura = $mod_asignatura->consultarAsignaturasxuacaid(1);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('index', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_periodoAcademico), "id", "name"),
                'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_asignatura), "id", "name"),
                'model' => $model,
            ]);       
    }

    public function actionCargarusuario() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        //$usu_id = Yii::$app->session->get('PB_iduser');
        $mod_educativa = new UsuarioEducativa();
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
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "educativa/" . $data["name_file"] . "." . $typeFile;
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
                
                $carga_archivo = $mod_educativa->CargarArchivoeducativa($data["archivo"]);
                if ($carga_archivo['status']) {
                    if (!empty($carga_archivo['noalumno'])){                        
                    $noalumno = ' No se encontró los usuarios '. $carga_archivo['noalumno'] . ' que no pertenecen a estudiantes en asgard. ';
                    //\app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $carga_archivo['noalumno']);
                    /*$nousuario = explode(",", $carga_archivo['noalumno']);
                    $arrData = array();
                    foreach ($nousuario as $user_noasgard) {  // empieza foreach genera usuarios no encontrados
                        $arrData =  $user_noasgard;                       
                    } // cierra foreach 
                    ini_set('memory_limit', '256M');
                    $content_type = Utilities::mimeContentType("xls");
                    $nombarch = "No estudiantes-" . date("YmdHis") . ".xls";
                    header("Content-Type: $content_type");
                    header("Content-Disposition: attachment;filename=" . $nombarch);
                    header('Cache-Control: max-age=0');
                    $colPosition = array("C", "D", "E", "F", "G");
                    $arrHeader = array(
                        Yii::t("formulario", "Usuario"),                        
                    );
                    $nameReport = academico::t("Academico", "Usuario que no estan en asgard");
                    Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
                    exit;*/
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
             return $this->render('cargarusuario', []);
        }
    }   
    public function actionDownloadplantilla() {
        $file = 'plantillaEducativa.xlsx';
                $route = str_replace("../", "", $file);
                $url_file = Yii::$app->basePath . "/uploads/educativa/" . $route;
                $arrfile = explode(".", $url_file);
                $typeImage = $arrfile[count($arrfile) - 1];
                if (file_exists($url_file)) {
                    if (strtolower($typeImage) == "xlsx") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/xlsx");
                        header('Content-Disposition: attachment; filename="plantillaEducativa' . time() . '.xlsx";');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_file));
                        readfile($url_file);
                    }
                }
    } 

    public function actionListarestudianteregistro() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $distributivo_model = new Distributivo();
        $mod_modalidad      = new Modalidad();
        $mod_unidad         = new UnidadAcademica();
        $mod_educativa      = new CursoEducativa();
        $mod_periodo        = new PeriodoAcademicoMetIngreso();
        $model_cursoest     = new CursoEducativaEstudiante();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"]      = $data['search'];
            $arrSearch["profesor"]    = $data['profesor'];
            $arrSearch["unidad"]      = $data['unidad'];
            $arrSearch["modalidad"]   = $data['modalidad'];
            $arrSearch["periodo"]     = $data['periodo'];
            $arrSearch["asignatura"]  = $data['asignatura'];
            $arrSearch["estado_pago"] = $data['estado'];
            //$arrSearch["jornada"] = $data['jornada'];
            $arrSearch["curso"] = $data['curso'];
            $model = $model_cursoest->consultarDistributivoxEducativa($arrSearch, 1);
            return $this->render('_listarestudiantesregistrogrid', [
                        "model" => $model,
            ]);
        } else {
            $model = $model_cursoest->consultarDistributivoxEducativa(null, 1);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getasignatura"])) {
                $asignatura = $distributivo_model->consultarAsiganturaxuniymoda($data["uaca_id"], $data["moda_id"]);
                $message = array("asignatura" => $asignatura);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcursoreg"])) {
                $periodoreg = $mod_educativa->consultarCursosxpacaid($data["codcursoreg"]);
                $message = array("periodoreg" => $periodoreg);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        $arr_periodo = $mod_periodo->consultarPeriodoAcademicotodos();
        $arr_asignatura = $distributivo_model->consultarAsiganturaxuniymoda(0, 0);
        $arr_curso = $mod_educativa->consultarCursosxpacaid(0); // parametro q envia es el paca_id
        return $this->render('listarestudianteregistro', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    "mod_periodo" => ArrayHelper::map($arr_periodo, "id", "name"),
                    'mod_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_asignatura), "id", "name"),
                    'model' => $model,
                    'mod_estado' => array("-1" => "Todos", "0" => "No Autorizado", "1" => "Autorizado"),
                    'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_curso), "id", "name"),
                    //'mod_jornada' => array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia"),
        ]);
    }

    public function actionExpexcelestregistro() {
        //$per_id = @Yii::$app->session->get("PB_perid");

        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
        $arrHeader = array(
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Complete Names"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Payment Status"),
            Yii::t("formulario", "Status")." Educativa",
        );
        //\app\models\Utilities::putMessageLogFile('perid:' . $per_id);
        $distributivo_model = new CursoEducativaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["estado_pago"] = $data['estado'];
        $arrSearch["curso"] = $data['curso'];
        //$arrSearch["jornada"] = $data['jornada'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $distributivo_model->consultarDistributivoxEducativa(array(), 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxEducativa($arrSearch, 0);
        }
        $nameReport = academico::t("Academico", "Listado de estudiantes registro");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfestregistro() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de estudiantes registro"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Complete Names"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Payment Status"),
            Yii::t("formulario", "Status")." Educativa",

        );
        $distributivo_model = new CursoEducativaEstudiante();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["estado_pago"] = $data['estado'];
        $arrSearch["curso"] = $data['curso'];
        //$arrSearch["jornada"] = $data['jornada'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $distributivo_model->consultarDistributivoxEducativa(array(), 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxEducativa($arrSearch, 0);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionUpload() {
        //$usu_id = Yii::$app->session->get("PB_iduser");   
        $mod_periodo = new PeriodoAcademicoMetIngreso(); 
        $mod_educativa = new CursoEducativa();
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
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "educativa/" . $data["name_file"] . "." . $typeFile;
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
                \app\models\Utilities::putMessageLogFile('Files controller entro ...: ');
                \app\models\Utilities::putMessageLogFile('paca_id controller ...: ' . $data["paca_id"]);
                $carga_archivo = $mod_educativa->CargarArchivocursoeducativa($data["archivo"], $data["paca_id"]);
                if ($carga_archivo['status']) {
                    \app\models\Utilities::putMessageLogFile('status controller entro...: ' . $arroout['noalumno']);
                    if (!empty($carga_archivo['noasignatura'])){                        
                    $noasignatura = ' Se encontró las Asignaturas'. $carga_archivo['noasignatura'] . ' que no corresponde el alias. ';
                    }
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data'] .  $noasignatura),
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
         } 
        else {
            //$arr_asignatura = Asignatura::findAll(["asi_estado" => 1, "asi_estado_logico" => 1]);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('cursoeducativa',[
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodoAcademico), "id", "name"),
                //'arr_asignatura' => (empty(ArrayHelper::map($arr_asignatura, "asi_id", "asi_nombre"))) ? array(Yii::t("cursoeducativa", "-- Select asignatura --")) : (ArrayHelper::map($arr_asignatura, "asi_id", "asi_nombre"))
            ]);
        }        
    }

    public function actionDownloadplantillacurso() {
        $file = 'Plantilla_cargarcursoeducativa.xlsx';
                $route = str_replace("../", "", $file);
                $url_file = Yii::$app->basePath . "/uploads/educativa/" . $route;
                $arrfile = explode(".", $url_file);
                $typeImage = $arrfile[count($arrfile) - 1];
                if (file_exists($url_file)) {
                    if (strtolower($typeImage) == "xlsx") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/xlsx");
                        header('Content-Disposition: attachment; filename="Plantilla_cargarcursoeducativa' . time() . '.xlsx";');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_file));
                        readfile($url_file);
                    }
                }
    }                           
    public function actionExpexcelestcurso() {
        //$per_id = @Yii::$app->session->get("PB_perid");

        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G");
        $arrHeader = array(
            Yii::t("formulario", "Period"),
            //Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Code"). ' Aula',
            academico::t("Academico", "Course"),           
        );
        //\app\models\Utilities::putMessageLogFile('perid:' . $per_id);
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];  
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarCursoEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarCursoEducativa($arrSearch, 0, 0);
        }
        $nameReport = academico::t("Academico", "Listado de cursos educativa");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfestcurso() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de cursos educativa"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "Period"),
            //Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Code"). ' Aula',
            academico::t("Academico", "Course"),
        );
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];  
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarCursoEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarCursoEducativa($arrSearch, 0, 0);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionNew() { 
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_asignatura = new Asignatura(); 
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            $arr_asignatura = $mod_asignatura->consultarAsignaturasxuacaid(1);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('new', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodoAcademico), "id", "name"),
                'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
                ]);       
    }
    public function actionSavecurso() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $periodo = $data["periodo"];
            //$materia = $data["materia"];
            $codigoaula = $data["codigoaula"];
            $nombreaula = ucwords(strtolower($data["nombreaula"]));
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $mod_educativa = new CursoEducativa();
                //  valida que no exista el registro OJO REVISAR BIEN CON EL ASI_ID ES NECESARIO         
                $existe = $mod_educativa->consultarcursoeducativaexi($periodo, $codigoaula, $nombreaula);
                //\app\models\Utilities::putMessageLogFile('existe rcurso...: ' . $existe['existe_curso']);     
                if ($existe['existe_curso'] == 0) {
                    $savecurso = $mod_educativa->insertarCursoeducativa($periodo, /*$materia,*/ $codigoaula, $nombreaula, $usuario);
                    if ($savecurso) {
                        $exito = 1;
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Se ha guardado el curso."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }
               }else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Ya creó anteriormente el curso." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
                
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionView() { 
        $cedu_id = base64_decode($_GET["cedu_id"]);
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_asignatura = new Asignatura(); 
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            // consultar la informacion del curso por cedu_id
            $arr_curso = $mod_educativa->consultarCursoxid($cedu_id);        
            $arr_asignatura = $mod_asignatura->consultarAsignaturasxuacaid(1);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('view', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodoAcademico), "id", "name"),
                'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
                'arr_curso' => $arr_curso,
            ]);       
    }
    // Se habilita por aun falta analizar bien los cambios en esta vista
    public function actionEdit() { 
        $cedu_id = base64_decode($_GET["cedu_id"]);
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_asignatura = new Asignatura(); 
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            //$cedu_id = 1;
            // consultar la infomracion del curso por cedu_id
            $arr_curso = $mod_educativa->consultarCursoxid($cedu_id);        
            $arr_asignatura = $mod_asignatura->consultarAsignaturasxuacaid(1);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('edit', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodoAcademico), "id", "name"),
                'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_asignatura), "id", "name"),
                'arr_curso' => $arr_curso,
            ]);       
    }

    public function actionEditcurso() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $cedu_id = $data["ceduid"];
            $periodo = $data["periodo"];
            //$materia = $data["materia"];
            $codigoaula = $data["codigoaula"];
            $nombreaula = ucwords(strtolower($data["nombreaula"]));
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $mod_educativa = new CursoEducativa();
                $editcurso = $mod_educativa->modificarCursoeducativa($cedu_id, $periodo, $codigoaula, $nombreaula, $usuario);
                    if ($editcurso) {
                        $exito = 1;
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Se ha modificado el curso."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al modificar." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }             
                
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al modificar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionDeletecurso() {
        $mod_educativa = new CursoEducativa();
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $cur_id = $data["cur_id"];           
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                //\app\models\Utilities::putMessageLogFile('curso id..: ' . $cur_id);     
                $resp_estado = $mod_educativa->eliminarCurso($cur_id, $usu_autenticado, $fecha);
                if ($resp_estado) {
                    $exito = '1';
                }
                if ($exito) {
                    //Realizar accion                    
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha eliminado el curso."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al eliminar. "),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al realizar la acción. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }
    public function actionIndexunidad() { 
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_asignatura = new Asignatura(); 
        $mod_educativa = new CursoEducativa();
        $mod_educativaunidad = new CursoEducativaUnidad();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];  
            $arrSearch["periodo"] = $data['periodo'];  
            $arrSearch["curso"] = $data['curso'];                               
            $model = $mod_educativaunidad->consultarUnidadEducativa($arrSearch, 1, 1);
            return $this->render('indexunidad-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $mod_educativaunidad->consultarUnidadEducativa(null, 1, 1);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();  
            if (isset($data["getcurso"])) {
                $periodo = $mod_educativa->consultarCursosxpacaid($data["codcurso"]);
                $message = array("periodo" => $periodo);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }          
        }   
            $arr_asignatura = $mod_asignatura->consultarAsignaturasxuacaid(1);
            $arr_curso = $mod_educativa->consultarCursosxpacaid(0);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('indexunidad', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_periodoAcademico), "id", "name"),
                'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_asignatura), "id", "name"),
                'model' => $model,
                'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_curso), "id", "name"),
            ]);       
    }

    public function actionExpexcelunidad() {
        //$per_id = @Yii::$app->session->get("PB_perid");

        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G");
        $arrHeader = array(
            academico::t("Academico", "Course"), 
            Yii::t("formulario", "Code"). ' '. Yii::t("formulario", "Unit"),
            Yii::t("formulario", "Description"),       
                      
        );
      
        $mod_educativa = new CursoEducativaUnidad();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["curso"] = $data['curso'];  
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarUnidadEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarUnidadEducativa($arrSearch, 0, 0);
        }
        $nameReport = academico::t("Academico", "Listado de unidades");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfunidad() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de unidades"); // Titulo del reporte
        $arrHeader = array(
            academico::t("Academico", "Course"), 
            Yii::t("formulario", "Code"). ' '. Yii::t("formulario", "Unit"),
            Yii::t("formulario", "Description"),
        );
        $mod_educativa = new CursoEducativaUnidad();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["curso"] = $data['curso'];  
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarUnidadEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarUnidadEducativa($arrSearch, 0, 0);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
    public function actionNewunidad() { 
        $mod_periodo = new PeriodoAcademicoMetIngreso();       
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();   
            if (isset($data["getcursounidad"])) {
                $periodounidad = $mod_educativa->consultarCursosxpacaid($data["codcursounidad"]);
                $message = array("periodounidad" => $periodounidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }          
        }   
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            $arr_curso = $mod_educativa->consultarCursosxpacaid(0);
            return $this->render('newunidad', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodoAcademico), "id", "name"),
                'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_curso), "id", "name"),
                ]);       
    }

    public function actionSaveunidad() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $curso = $data["curso"];            
            $codigounidad = $data["codigounidad"];
            $nombreunidad = ucwords(strtolower($data["nombreunidad"]));
            $fechainicio = $data["fechainiciog"];
            $fechafin = $data["fechafing"];
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $mod_educativa = new CursoEducativaUnidad();
                $existe = $mod_educativa->consultarunidadexiste($curso, $codigounidad, $nombreunidad);
                //\app\models\Utilities::putMessageLogFile('existe curso...: ' . $existe['existe_curso']);     
                if ($existe['existe_curso'] == 0) {
                    $savecurso = $mod_educativa->insertarUnidadeducativa($curso, $codigounidad, $nombreunidad, $usuario, $fechainicio, $fechafin);
                    if ($savecurso) {
                        $exito = 1;
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Se ha guardado la unidad."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }
               }else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Ya creó anteriormente la unidad." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
                
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }
    
    public function actionViewunidad() { 
        // $cedu_id = base64_decode($_GET["cedu_id"]);
        $ceuni_id = base64_decode($_GET["ceuni_id"]);
        $mod_periodo = new PeriodoAcademicoMetIngreso();        
        $mod_educativa = new CursoEducativa();
        $mod_unidad = new CursoEducativaUnidad();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {                       
        }  
            // consultar la informacion de la unidad por ceuni_id
            $arr_unidad = $mod_unidad->consultarUnidadxid($ceuni_id); 
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            // se debe pasar elparametro del id del curso de la consultarUnidadxid
            $arr_curso = $mod_educativa->consultarCursosxpacaid($arr_unidad['paca_id']);
            return $this->render('viewunidad', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodoAcademico), "id", "name"),
                'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_curso), "id", "name"),
                'arr_unidad' => $arr_unidad,
                ]);            
    }

    public function actionEditunidad() { 
        // $cedu_id = base64_decode($_GET["cedu_id"]);
        $ceuni_id = base64_decode($_GET["ceuni_id"]);
        $mod_periodo = new PeriodoAcademicoMetIngreso();        
        $mod_educativa = new CursoEducativa();
        $mod_unidad = new CursoEducativaUnidad();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();  
            $data = Yii::$app->request->post(); 
            if (isset($data["getcursounidades"])) {
                $periodounidades = $mod_educativa->consultarCursosxpacaid($data["codcursounidades"]);
                $message = array("periodounidades" => $periodounidades);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }           
        }  
            // consultar la informacion de la unidad por ceuni_id
            $arr_unidad = $mod_unidad->consultarUnidadxid($ceuni_id); 
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            // se debe pasar elparametro del id del curso de la consultarUnidadxid
            $arr_curso = $mod_educativa->consultarCursosxpacaid($arr_unidad['paca_id']);
            return $this->render('editunidad', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_periodoAcademico), "id", "name"),
                'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_curso), "id", "name"),
                'arr_unidad' => $arr_unidad,
                ]);            
    }

    public function actionUpdateunidad() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuario = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $ceuni_id = $data["ceuni_id"];
            $cursodounidad = $data["cursodounidad"];
            $codigounidad = $data["codigounidad"];            
            $fechainicio = $data["fechainicioed"];
            $fechafin = $data["fechafined"];
            $nombreunidad = ucwords(strtolower($data["nombreunidad"]));
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $mod_educativa = new CursoEducativaUnidad();
                $editunidad = $mod_educativa->modificarUnidadeducativa($ceuni_id, $cursodounidad, $codigounidad, $nombreunidad, $usuario, $fechainicio, $fechafin);
                    if ($editunidad) {
                        $exito = 1;
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Se ha modificado la unidad."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al modificar." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }             
                
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al modificar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionDeleteunidad() {
        $mod_educativa = new CursoEducativaUnidad();
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $ceuni_id = $data["ceuni_id"];           
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                //\app\models\Utilities::putMessageLogFile('ceuni_id..: ' . $ceuni_id);     
                $resp_estado = $mod_educativa->eliminarUnidad($ceuni_id, $usu_autenticado, $fecha);
                if ($resp_estado) {
                    $exito = '1';
                }
                if ($exito) {
                    //Realizar accion                    
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha eliminado la unidad."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error la unidad. "),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al realizar la acción. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }

    public function actionUploadunidad() {
        //$usu_id = Yii::$app->session->get("PB_iduser");          
        $mod_educativa = new CursoEducativaUnidad();
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
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "educativa/" . $data["name_file"] . "." . $typeFile;
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
                //\app\models\Utilities::putMessageLogFile('Files controller entro ...: ');
                //\app\models\Utilities::putMessageLogFile('paca_id controller ...: ' . $data["paca_id"]);
                $carga_archivo = $mod_educativa->CargarArchivounidadeducativa($data["archivo"]);
                if ($carga_archivo['status']) {
                    \app\models\Utilities::putMessageLogFile('status controller entro...: ' . $arroout['noalumno']);
                    if (!empty($carga_archivo['nocurso'])){                        
                    $nocurso = ' No se encontro los siguientes códigos de cursos '. $carga_archivo['nocurso'];
                    }
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente." . $carga_archivo['data'] .  $nocurso),
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
         } 
        else {
            return $this->render('unidadeducativa',[
                
            ]);
        }        
    }

    public function actionDownloadplantillaunidad() {
        $file = 'Plantilla_cargarunidadeducativa.xlsx';
                $route = str_replace("../", "", $file);
                $url_file = Yii::$app->basePath . "/uploads/educativa/" . $route;
                $arrfile = explode(".", $url_file);
                $typeImage = $arrfile[count($arrfile) - 1];
                if (file_exists($url_file)) {
                    if (strtolower($typeImage) == "xlsx") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/xlsx");
                        header('Content-Disposition: attachment; filename="Plantilla_cargarunidadeducativa' . time() . '.xlsx";');
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_file));
                        readfile($url_file);
                    }
                }
    } 
    
    public function actionAsignarestudiantecurso() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $distributivo_model = new Distributivo();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_educativa = new CursoEducativa();
        $mod_asignar = new CursoEducativaEstudiante();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            //$arrSearch["search"] = $data['search'];
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["periodo"] = $data['periodo'];
            $arrSearch["asignatura"] = $data['asignatura'];
            $arrSearch["curso"] = $data['curso'];
            $arrSearch["estado"] = $data['estado'];
            // este query cambiar a uno igual pero con mas cosa para no dañar el origina
            $model = $mod_asignar->consultarDistributivoasigest($arrSearch, 1);
            return $this->render('_asignarestudiantecursogrid', [
                        "model" => $model,
            ]);
        } else {
            // este query cambiar a uno igual pero con mas cosa para no dañar el origina
            $model = $mod_asignar->consultarDistributivoasigest(null, 1);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidadasi"])) {
                $modalidadasi = $mod_modalidad->consultarModalidad($data["uaca_ids"], 1);
                $message = array("modalidadasi" => $modalidadasi);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getasignaturasi"])) {
                $asignaturasi = $distributivo_model->consultarAsiganturaxuniymoda($data["uaca_ids"], $data["moda_ids"]);
                $message = array("asignaturasi" => $asignaturasi);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcursoasi"])) {
                $periodoasi = $mod_educativa->consultarCursosxpacaid($data["codcursoasi"]);
                $message = array("periodoasi" => $periodoasi);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        $arr_periodo = $mod_periodo->consultarPeriodoAcademicotodos();
        $arr_asignatura = $distributivo_model->consultarAsiganturaxuniymoda(0, 0);
        $arr_curso = $mod_educativa->consultarCursosxpacaid(0); // parametro q envia es el paca_id

        return $this->render('asignarestudiantecurso', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'mod_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_periodo), "id", "name"),
                    'mod_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_asignatura), "id", "name"),
                    'model' => $model,
                    'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_curso), "id", "name"),
                    'mod_estado' => array("-1" => "Todos", "0" => "Asignado", "1" => "No asignado"),                    
        ]);
    }

    public function actionExpexceleduasignar() {
        //$per_id = @Yii::$app->session->get("PB_perid");

        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        $arrHeader = array(
            Yii::t("formulario", "Code"). ' '. Yii::t("formulario", "Student"), 
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"), 
            Yii::t("formulario", "Student"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            academico::t("Academico", "Course"),
            // Yii::t("formulario", 'Status'),
        );
      
        $mod_asignar = new CursoEducativaEstudiante();
        $data = Yii::$app->request->get();
        //$arrSearch["search"] = $data['search'];
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["curso"] = $data['curso'];
        $arrSearch["estado"] = $data['estado'];
        if ($arrSearch["estado"] == '0') {
            $estadoasig = "Asignado" ;
        }elseif ($arrSearch["estado"] == '1'){
            $estadoasig = "No Asignado" ;
        }else {
            $estadoasig = " " ;
        }
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_asignar->consultarDistributivoasigest(array(), 0, 0);
        } else {
            $arrData = $mod_asignar->consultarDistributivoasigest($arrSearch, 0, 0);
        }
        $nameReport = academico::t("Academico", "Listado de Estudiantes ") .$estadoasig;
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfeduasignar() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de Estudiantes ").$estadoasig; // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "Code"). ' '. Yii::t("formulario", "Student"), 
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"), 
            Yii::t("formulario", "Student"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            academico::t("Academico", "Course"),
            //Yii::t("formulario", 'Status'),
        );
        $mod_asignar = new CursoEducativaEstudiante();
        $data = Yii::$app->request->get();
        //$arrSearch["search"] = $data['search'];
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        //$arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["curso"] = $data['curso'];
        $arrSearch["estado"] = $data['estado'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_asignar->consultarDistributivoasigest(array(), 0, 0);
        } else {
            $arrData = $mod_asignar->consultarDistributivoasigest($arrSearch, 0, 0);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionSavestudiantescurso() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $paca_id = $data["periodo"];
            $cedu_id = $data["curso"];
            $asignado = $data["asignado"];
            $noasignado = $data["noasignado"];

            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if (!empty($asignado)) {
                    $asignados = explode(",", $asignado); //ASIGNADOS
                    foreach ($asignados as $est_id) {  // empieza foreach para guardar los asignados
                        //Verificar que no haya guardado el estudiante en el cedu_id y est_id para insert.
                        // en un ciclo primero los pagados, luego los no pagado
                        $mod_asignar = new CursoEducativaEstudiante();
                        $resp_consAsignacion = $mod_asignar->consultarAsignacionexiste($cedu_id, $est_id);
                        if ($resp_consAsignacion["exiteasigna"] == 0) {
                            // update pagados   
                            //$resp_modificarpago = $mod_asignar->modificarPagoestudiante($periodo, null, $est_id, 1, $usu_id);
                            //$exito = 1;
                        //} else {
                            // es un insert pagados
                            $resp_guardarpago = $mod_asignar->insertarEstudiantecurso($cedu_id, $est_id, $usu_id);
                            $exito = 1;
                        }
                    } // cierra foreach 
                }
                /*if (!empty($nopagado)) {
                    $nopagados = explode(",", $nopagado); //NO PAGADOS
                    foreach ($nopagados as $est_id) {  // empieza foreach para guardar los no pagados
                        //Verificar que no haya guardado el estudiante en el periodo y est_id para insert, si guardo es update NO PAGADOS.                    
                        $mod_asignar = new CursoEducativaEstudiante();
                        $resp_consPeriodonopago = $mod_asignar->consultarPeriodopago($periodo, null, $est_id);
                        if (!empty($resp_consPeriodonopago["eppa_id"])) {
                            // update NO pagados 
                            $resp_guardanopago = $mod_asignar->modificarPagoestudiante($periodo, null, $est_id, 0, $usu_id);
                            $exito = 1;
                        } else {
                            // es un insert NO pagados
                            $resp_modificarnopago = $mod_asignar->insertarPagoestudiante($periodo, null, $est_id, 0, $usu_id);
                            $exito = 1;
                        }
                    } // cierra foreach 
                }*/
                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La información ha sido grabada."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
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
    public function actionUsuarioindex() { 
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_asignatura = new Asignatura(); 
        $mod_educativa = new UsuarioEducativa();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];  
            $model = $mod_educativa->consultarUsuarioEducativa($arrSearch, 1, 1);
            return $this->render('usuarioindex-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $mod_educativa->consultarUsuarioEducativa(null, 1, 1);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            $arr_asignatura = $mod_asignatura->consultarAsignaturasxuacaid(1);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('usuarioindex', [  
                'model' => $model,
            ]);       
    }

    public function actionExpexcelusuarioedu() {       
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G");
        $arrHeader = array(
            Yii::t("formulario", "Users"),
            Yii::t("formulario", "Names"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Enrollment"), 
            Yii::t("formulario", "Email"),         
        );
        $mod_educativa = new UsuarioEducativa();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];        
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarUsuarioEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarUsuarioEducativa($arrSearch, 0, 0);
        }
        $nameReport = academico::t("Academico", "Listado de usuarios educativa");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfusuarioedu() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de usuarios educativa"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "Users"),
            Yii::t("formulario", "Names"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Enrollment"), 
            Yii::t("formulario", "Email"),
        );
        $mod_educativa = new UsuarioEducativa();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];        
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarUsuarioEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarUsuarioEducativa($arrSearch, 0, 0);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionDeleteusuario() {
        $mod_educativa = new UsuarioEducativa();
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $uedu_id = $data["uedu_id"];           
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                //\app\models\Utilities::putMessageLogFile('uedu_id ..: ' . $uedu_id);     
                $resp_estado = $mod_educativa->eliminarUsuario($uedu_id, $usu_autenticado, $fecha);
                if ($resp_estado) {
                    $exito = '1';
                }
                if ($exito) {
                    //Realizar accion                    
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha eliminado el usuario."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al eliminar. "),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al realizar la acción. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }
    public function actionViewusuario() { 
        $uedu_id = base64_decode($_GET["uedu_id"]);        
        $mod_educativa = new UsuarioEducativa();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            // consultar la informacion del usuario por id
            $arr_usuario = $mod_educativa->consultarUsuarioxid($uedu_id);        
            return $this->render('viewusuario', [  
                'arr_usuario' => $arr_usuario,
            ]);       
    }

    public function actionEditusuario() { 
        $uedu_id = base64_decode($_GET["uedu_id"]);        
        $mod_educativa = new UsuarioEducativa();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            // consultar la informacion del usuario por id
            $arr_usuario = $mod_educativa->consultarUsuarioxid($uedu_id);        
            return $this->render('editusuario', [  
                'arr_usuario' => $arr_usuario,
            ]);       
    }

    public function actionEditarusuario() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuariomod = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $uedu_id = base64_decode($data["uedu_id"]);
            $usuario = $data["usuario"];
            $nombre = ucwords(strtolower($data["nombre"]));
            $apellido = ucwords(strtolower($data["apellido"]));
            $cedula = $data["cedula"];
            $matricula = $data["matricula"];
            $correo = strtolower($data["correo"]);
            /*\app\models\Utilities::putMessageLogFile('uedu_id ..: ' . $uedu_id);
            \app\models\Utilities::putMessageLogFile('usuario ..: ' . $usuario);
            \app\models\Utilities::putMessageLogFile('nombre ..: ' . $nombre);
            \app\models\Utilities::putMessageLogFile('apellido ..: ' . $apellido);
            \app\models\Utilities::putMessageLogFile('cedula ..: ' . $cedula);
            \app\models\Utilities::putMessageLogFile('matricula ..: ' . $matricula);
            \app\models\Utilities::putMessageLogFile('correo ..: ' . $correo);*/
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $mod_educativa = new UsuarioEducativa();
                $editcurso = $mod_educativa->modificarUsuarioeducativa($uedu_id, $usuario, $nombre, $apellido, $cedula, $matricula, $correo, $usuariomod);
                    if ($editcurso) {
                        $exito = 1;
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Se ha modificado el usuario."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al modificar." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }             
                
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al modificar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionNewusuario() { 
        
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();            
        }   
            return $this->render('newusuario', [  
                ]);       
    }

    public function actionSaveusuario() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usuariomod = @Yii::$app->user->identity->usu_id;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $usuario = $data["usuario"];
            $nombre = ucwords(strtolower($data["nombre"]));
            $apellido = ucwords(strtolower($data["apellido"]));
            $cedula = $data["cedula"];
            $matricula = $data["matricula"];
            $correo = strtolower($data["correo"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $mod_educativa = new UsuarioEducativa();
                $existe = $mod_educativa->consultarexisteusuario($usuario, $correo, $cedula, $matricula);
                //\app\models\Utilities::putMessageLogFile('existe rcurso...: ' . $existe['existe_curso']);     
                if ($existe['existe_usuario'] == 0) {
                    // valida q ese usuario exista como estudiante OJO FALTA
                    $estudiante = $mod_educativa->consultarEstutudiantexusuario($usuario);
                    $est_id = $estudiante['est_id'];
                    $per_id = $estudiante['per_id'];
                    if (!empty($est_id) && !empty($per_id)) {
                    // enviar los est_id y per_id a la funcion de grabar
                    $saveusuario = $mod_educativa->insertarUsuarioeducativa($usuario, $est_id, $per_id, $nombre, $apellido, $cedula, $matricula, $correo, $usuariomod);
                    if ($saveusuario) {
                        $exito = 1;
                    }
                    if ($exito) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Se ha guardado el usuario."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar1." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }
                //cierra if linea 1506
            }else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "No se puede crear, porque usuario no se encuentra como estudiante en asgard." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            } 
               }else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Ya creó anteriormente el usuario." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
                
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
            return;
        }
    }

    public function actionSavestudiantesbloqueo() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data        = Yii::$app->request->post();  
            $cedu_id     = $data["curso"];
            $nobloqueado = $data["nobloqueado"];
            $bloqueado   = $data["bloqueado"];
            //\app\models\Utilities::putMessageLogFile('no bloqueo '. $nobloqueado);
            //print_r($data);die();
            /**********************************/
            /*
            $client = new \SoapClient("https://campusvirtual.uteg.edu.ec/soap/?wsdl=true", 
                                      array("login"    => Yii::$app->params["wsLogin"], 
                                            "password" => Yii::$app->params["wsPassword"],
                                     "trace" => 1, "exceptions" => 0));

            $client->setCredentials(Yii::$app->params["wsLogin"], 
                                    Yii::$app->params["wsPassword"],"basic");
            
            
            $method = 'obtener_prg_items';
            $args = Array('id_grupo'     =>  '2918',
                          'id_tipo_item' => 'EV',
                          'id_unidad'    => '52545' 
                         );  
            
              
            $method = 'asignar_usuarios_alcance_prg_items';
            $args = Array('asignar_usuario_item' => Array('id_usuario' => '202100034',
                                                            'id_prg_item' => '95341')); //21405
            
            $result = $client->__call( $method, Array( $args ) );

            return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $result);
            */
            /**********************************/

            $client = new \SoapClient("https://campusvirtual.uteg.edu.ec/soap/?wsdl=true", 
                                                  array("login" => Yii::$app->params["wsLogin"], 
                                                  "password"    => Yii::$app->params["wsPassword"],
                                                  "trace"       => 1, "exceptions" => 0));

            $client->setCredentials(Yii::$app->params["wsLogin"], Yii::$app->params["wsPassword"],"basic");

            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();

            $noactualizados = array();
            try {
                if (!empty($nobloqueado)) {
                    $nobloqueado = explode(",", $nobloqueado); //permitidos
                    foreach ($nobloqueado as $est_id) {  // empieza foreach para guardar los asignados

                        //Valida si el usuario tiene id de educativa
                        $mod_usuaedu = new UsuarioEducativa();
                        $result_usuaedu = $mod_usuaedu->consultarexisteusuarioxest($est_id);
                        $uedu_usuario   = $mod_usuaedu->usuarioeducativa($est_id);
                        
                        //Valida si esta asignado al curso virtual
                        $mod_asignar = new CursoEducativaEstudiante();
                        $resp_consAsignacion = $mod_asignar->consultarAsignacionexiste($cedu_id, $est_id);

                        if($result_usuaedu['existe_usuario'] > 0 && $resp_consAsignacion["exiteasigna"] > 0){

                            //Primero obtenemos el Id del Curso
                            $mod_ceducativa = new CursoEducativa();
                            $id_grupo_array = $mod_ceducativa->consultarCursoxid($cedu_id);
                            $id_grupo       = $id_grupo_array['cedu_asi_id'];

                            //Despues obtenemos los id de las unidades a bloquear
                            $mod_educativaunidad = new CursoEducativaUnidad();
                            $id_unidad_array     = $mod_educativaunidad->consultarUnidadEducativaxCeduid($cedu_id);

                            $obtener_prg_items = array();

                            foreach ($id_unidad_array as $key => $value) {
                                $method = 'obtener_prg_items';
                                $args = Array('id_grupo'     =>  $id_grupo,
                                              'id_tipo_item' => 'EV',
                                              'id_unidad'    => $value['ceuni_codigo_unidad']
                                             );  
                                $result = $client->__call( $method, Array( $args ) );

                                
                                array_push($obtener_prg_items,$result);

    
                                $prg_item = $result->prg_item;
                                if(isset($prg_item->id_prg_item)){
                                    $method = 'asignar_usuarios_alcance_prg_items';

                                    //print_r($uedu_usuario);die();
                                    $args = Array('asignar_usuario_item' => Array('id_usuario'  => $uedu_usuario['uedu_usuario'], 
                                                                                  'id_prg_item' => $prg_item->id_prg_item));
            
                                    $result = $client->__call( $method, Array( $args ) );

                                    $resp_guardarbloqueo = $mod_asignar->modificarEstadobloqueo($cedu_id, $est_id, 'A', $usu_id);
                                }   
                                else{
                                    if(count($prg_item) > 0){
                                        foreach ($prg_item as $key => $value) {
                                            $method = 'asignar_usuarios_alcance_prg_items';
                                            $args = Array('asignar_usuario_item' => Array('id_usuario'  => $uedu_usuario['uedu_usuario'], 
                                                                                         'id_prg_item' => $value->id_prg_item));
            
                                            $result = $client->__call( $method, Array( $args ) );

                                            $resp_guardarbloqueo = $mod_asignar->modificarEstadobloqueo($cedu_id, $est_id, 'A', $usu_id);
                                        }//foreach
                                    }//if
                                }//else
                                
                            }//foreach

                            $exito = 1;
                        }else{
                            array_push($noactualizados,$est_id);
                            $exito = 1;
                        }


                        /*

                        if ($resp_consAsignacion["exiteasigna"] > 0) {
                            // update estado bloqueo   
                            
                        } else {
                            // no estan asignados, mostrar mensaje
                            $exito = 1;
                            /*$transaction->rollback();
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Los Estudiantes que no se cambio estado es que no estan asignados a un curso "),
                                "title" => Yii::t('jslang', 'Error'),
                            );
                            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);*/
                        //}
                        
                    } // cierra foreach 
                }
                // SI AL ESTAR SIN SELECCIONAR SE CAMBIA A NO PERMITIDO
                /*if (!empty($bloqueado)) {
                    $bloqueado = explode(",", $bloqueado); //bloqueado
                    foreach ($nobloqueado as $est_id) {  // empieza foreach para guardar los asignados
                        $mod_asignar = new CursoEducativaEstudiante();
                        $resp_consAsignacion = $mod_asignar->consultarAsignacionexiste($cedu_id, $est_id);
                        if ($resp_consAsignacion["exiteasigna"] == 0) {
                            // update estado bloqueo    
                            $resp_guardarbloqueo = $mod_asignar->modificarEstadobloqueo($cedu_id, $est_id, 'B', $usu_id);
                            $exito = 1;
                        } /*else {
                            // no estan asignados, mostrar mensaje
                            $transaction->rollback();
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Los Estudiantes que no se cambio estado es que no estan asignados a un curso "),
                                "title" => Yii::t('jslang', 'Error'),
                            );
                            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                        }
                    } // cierra foreach 
                }*/
                if ($exito) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La información ha sido grabada. Los Estudiantes que no se cambio estado, es que no estan asignados a un curso"),
                        "title" => Yii::t('jslang', 'Success'),
                        "noactualizados" => $noactualizados,
                        "obtener_prg_items" => $obtener_prg_items,
                    );
                    return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
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

    public function actionAsignardistributivo() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $emp_id = @Yii::$app->session->get("PB_idempresa");
        $model = NULL;
        $distributivo_model = new CursoEducativaDistributivo();
        $modeljornada = new DistributivoAcademico();
        $modelo_dist = new Distributivo();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_educativa = new CursoEducativa();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $reporte = null;
            $search = $data['search'];
            $unidad = (isset($data['unidad']) && $data['unidad'] > 0) ? $data['unidad'] : NULL;
            $modalidad = (isset($data['modalidad']) && $data['modalidad'] > 0) ? $data['modalidad'] : NULL;
            $periodo = (isset($data['periodo']) && $data['periodo'] > 0) ? $data['periodo'] : NULL;
            $materia = (isset($data['materia']) && $data['materia'] > 0) ? $data['materia'] : NULL;
            $jornada = (isset($data['jornada']) && $data['jornada'] > 0) ? $data['jornada'] : NULL;
            $model = $distributivo_model->getListadoDistributivoedu($search, $modalidad, $materia, $jornada, $unidad, $periodo, $reporte);
            return $this->render('asignar-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $distributivo_model->getListadoDistributivoedu();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], $emp_id);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getasignaturasig"])) {
                $asignaturasig = $modelo_dist->consultarAsiganturaxuniymoda($data["uaca_ides"], $data["moda_ides"]);
                $message = array("asignaturasig" => $asignaturasig);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getjornada"])) {
                //\app\models\Utilities::putMessageLogFile('jor unudad...: ' . $data["uaca_isd"]);     
                //\app\models\Utilities::putMessageLogFile('jor mod...: ' . $data["mod_isd"]);     
                $jornada = $modeljornada->getJornadasByUnidadAcad($data["uaca_isd"], $data["mod_isd"]);
                $message = array("jornada" => $jornada);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            /*if (isset($data["gethorario"])) {
                $horario = $distributivo_model->getHorariosByUnidadAcad($data["uaca_id"], $data["mod_id"], $data['jornada_id']);
                $message = array("horario" => $horario);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }*/
        }
        $mod_asignatura = Asignatura::findAll(['asi_estado' => 1, 'asi_estado_logico' => 1]);
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa($emp_id);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        $arr_jornada = $modeljornada->getJornadasByUnidadAcad(0,0/*$arr_unidad[0]["id"], $arr_modalidad[0]["id"]*/);
        $arr_periodo = $mod_periodo->consultarPeriodoAcademico();
        //$arr_curso = $mod_educativa->consultarCursosxpacaid(0); // parametro q envia es el paca_id
        //$arr_curso = $mod_educativa->consultarCursostodos();
        return $this->render('asignardistributivo', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'mod_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
                    'mod_materias' => ArrayHelper::map(array_merge([["asi_id" => "0", "asi_nombre" => Yii::t("formulario", "Grid")]], $mod_asignatura), "asi_id", "asi_nombre"),
                    'model' => $model,
                    'mod_jornada' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_jornada), "id", "name"),
                    //'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_curso), "id", "name"),
        ]);
    }

    /**
     * Función para insertar los estudiantes en curso_educativa_estudiante, desde la vista de asgard/academico/usuarioeducativa/listarestudianteregistro
     * @author Jorge Paladines <analista.desarrollo@uteg.edu.ec>;
     * @param
     * @return
     */
    public function actionInsertarestudiantes(){
        $usu_id = Yii::$app->session->get('PB_iduser');
        $mod_cursoeduc = new CursoEducativaEstudiante();
        $ids = $mod_cursoeduc->consultarCursoEducativaDistributivoPeriodoActual();
        $tam = count($ids);

        try{
            foreach ($ids as $key => $value) {
                $est_id = $value['est_id'];
                $cedu_id = $value['cedu_id'];
                // $daca_id = $value['daca_id'];

                $hasRegistro = CursoEducativaEstudiante::find()->where(['est_id' => $est_id, 'cedu_id' => $cedu_id])->asArray()->one();
                if(isset($hasRegistro)){
                    $tam -= 1;
                }
                else{
                    $insertID = $mod_cursoeduc->insertarEstudiantecurso($cedu_id, $est_id, $usu_id);
                }
            }
            if($insertID){
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully update."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            elseif ($tam <= 0) {
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Todos los estudiantes ya han sido agregados"),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            else{
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error"),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
        }
        catch (Exception $ex) {
            $message = array(
                "wtmessage" => Yii::t("notificaciones", "Error".$ex),
                "title" => Yii::t('jslang', 'Error'),
            );
            return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
        }
    }

    public function actionExpexcelasigd() {
        $per_id = @Yii::$app->session->get("PB_perid");
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrHeader = array(
            academico::t("Academico", "Id"),
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            academico::t("Academico", "Working day"),
        );
        $distributivo_model = new CursoEducativaDistributivo();
        $data = Yii::$app->request->get();
        $reporte = 1;
        $arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
        $arrSearch["unidad"] = ($data['unidad'] > 0) ? $data['unidad'] : NULL;
        $arrSearch["modalidad"] = ($data['modalidad'] > 0) ? $data['modalidad'] : NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0) ? $data['periodo'] : NULL;
        $arrSearch["materia"] = ($data['materia'] > 0) ? $data['materia'] : NULL;
        $arrSearch["jornada"] = ($data['jornada'] > 0) ? $data['jornada'] : NULL;

        $arrData = $distributivo_model->getListadoDistributivoedu($arrSearch["search"], $arrSearch["modalidad"], $arrSearch["materia"], $arrSearch["jornada"], $arrSearch["unidad"], $arrSearch["periodo"], true, $reporte);
        foreach ($arrData as $key => $value) {
            unset($arrData[$key]["Id"]);
        }
        $nameReport = academico::t("distributivoacademico", "Profesor Lists by Subject");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdasigd() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("distributivoacademico", "Profesor Lists by Subject"); // Titulo del reporte
        $arrHeader = array(
            academico::t("Academico", "Id"),
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            academico::t("Academico", "Working day"),
        );
        $distributivo_model = new CursoEducativaDistributivo();
        $data = Yii::$app->request->get();
        $reporte = 1;
        $arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
        $arrSearch["unidad"] = ($data['unidad'] > 0) ? $data['unidad'] : NULL;
        $arrSearch["modalidad"] = ($data['modalidad'] > 0) ? $data['modalidad'] : NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0) ? $data['periodo'] : NULL;
        $arrSearch["materia"] = ($data['materia'] > 0) ? $data['materia'] : NULL;
        $arrSearch["jornada"] = ($data['jornada'] > 0) ? $data['jornada'] : NULL;

        $arrData = $distributivo_model->getListadoDistributivoedu($arrSearch["search"], $arrSearch["modalidad"], $arrSearch["materia"], $arrSearch["jornada"], $arrSearch["unidad"], $arrSearch["periodo"], true, $reporte);
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionSavedistributivo() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $distributivo_model = new CursoEducativaDistributivo();    
        //$fecha_transaccion = date(Yii::$app->params["dateTimeByDefault"]);
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();  
            $uaca_id = $data["uaca_id"];
            $mod_id = $data["mod_id"];
            $paca_id = $data["paca_id"];
            $asig_id = $data["asig_id"]; 
            /*\app\models\Utilities::putMessageLogFile('Graba Curso $uaca_id '. $uaca_id); 
            \app\models\Utilities::putMessageLogFile('Graba Curso $mod_id '. $mod_id); 
            \app\models\Utilities::putMessageLogFile('Graba Curso $paca_id ' . $paca_id); 
            \app\models\Utilities::putMessageLogFile('Graba Curso $asig_id ' . $asig_id);*/
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try{
                    //\app\models\Utilities::putMessageLogFile('Graba Curso');                         
                    for ($i = 0; $i < sizeof($data["nombre"]); $i++) {  
                        $cedu_id =  $data["nombre"][$i]["codigo_curso"];
                        $pro_id = $data["nombre"][$i]["profesor"]; //ESTE FALTA EN EL GRID OJO
                        //\app\models\Utilities::putMessageLogFile('Graba profesor $pro_id ' . $pro_id);
                        //obtengo daca_id
                        $daca_id = $distributivo_model->consultarDistribuAca($paca_id, $asig_id, $pro_id, $uaca_id, $mod_id);
                        if ($daca_id["daca_id"] > 0) {
                        $respCurso = $distributivo_model->consultarEdudistributivoexiste($cedu_id, $daca_id["daca_id"]);
                        if ($respCurso["exitedistributivo"] == 0) {
                               $respdist = $distributivo_model->insertarEstudiantecurso($cedu_id, $daca_id["daca_id"], $usu_id);
                               $exito = 1;
                         } else {
                            $transaction->rollback();
                           $message = array(
                           "wtmessage" => Yii::t('notificaciones', 'Error al grabar. Ya ha asignado un distriutivo educativa con estos datos'),
                           "title" => Yii::t('jslang', 'Error'),
                           );
                           return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), false, $message);
                       }
                        } else {
                            $transaction->rollback();
                           $message = array(
                           "wtmessage" => Yii::t('notificaciones', 'Error al grabar. No existe un distributivo academico con esa información'),
                           "title" => Yii::t('jslang', 'Error'),
                           );
                           return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), false, $message);
                       }
                    }//fin for
                    if ($exito) {
                         $transaction->commit();
                        $message = array(
                        "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. "),
                        "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                         $transaction->rollback();
                        $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Su información no ha sido grabada. Por favor intente nuevamente o contacte al área de Desarrollo.'),
                        "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), false, $message);
                    }
               
                }
            catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Errorcvb" . $mensaje),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
            return;     
        }    
    }

    // return $this->render('distributivoindex', [  
                 
    public function actionDistributivoindex() { 
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_asignatura = new Asignatura(); 
        $mod_educativa = new CursoEducativa();
        $mod_educativaunidad = new CursoEducativaDistributivo();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];  
            $arrSearch["periodo"] = $data['periodo'];  
            $arrSearch["curso"] = $data['curso'];                               
            $model = $mod_educativaunidad->consultarDistEducativa($arrSearch, 1, 1);
            return $this->render('distributivoindex-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $mod_educativaunidad->consultarDistEducativa(null, 1, 1);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();  
            if (isset($data["getcursos"])) {
                $periodos= $mod_educativa->consultarCursosxpacaid($data["codcursos"]);
                $message = array("periodos" => $periodos);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }          
        }   
            $arr_asignatura = $mod_asignatura->consultarAsignaturasxuacaid(1);
            $arr_curso = $mod_educativa->consultarCursosxpacaid(0);
            $arr_periodoAcademico = $mod_periodo->consultarPeriodoAcademicotodos();
            return $this->render('distributivoindex', [  
                'arr_periodoAcademico' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_periodoAcademico), "id", "name"),
                'arr_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_asignatura), "id", "name"),
                'model' => $model,
                'arr_curso' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "All")]], $arr_curso), "id", "name"),
            ]);       
    }

    public function actionExpexceldistedu() {
        //$per_id = @Yii::$app->session->get("PB_perid");

        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J");
        $arrHeader = array(
            academico::t("Academico", "Course"),
            academico::t("Academico", "Aca. Uni."),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Subject"),
            academico::t("Academico", "Teacher"),
        );
      
        $mod_educativa = new CursoEducativaDistributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["curso"] = $data['curso'];  
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarDistEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarDistEducativa($arrSearch, 0, 0);
        }
        $nameReport = academico::t("Academico", "Listado de distributivo por profesor");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfdistedu() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de distributivo por profesor"); // Titulo del reporte
        $arrHeader = array(
            academico::t("Academico", "Course"),
            academico::t("Academico", "Aca. Uni."),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Subject"),
            academico::t("Academico", "Teacher"), 
        );
        $mod_educativa = new CursoEducativaDistributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["curso"] = $data['curso'];  
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_educativa->consultarDistEducativa(array(), 0, 0);
        } else {
            $arrData = $mod_educativa->consultarDistEducativa($arrSearch, 0, 0);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionDeletedistributivo() {
        $mod_educativa = new CursoEducativaDistributivo();
        $usu_autenticado = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $cedi_id = $data["cedi_id"];           
            $fecha = date(Yii::$app->params["dateTimeByDefault"]);
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                //\app\models\Utilities::putMessageLogFile('cedi_id ..: ' . $cedi_id);     
                $resp_estado = $mod_educativa->eliminarDistributivo($cedi_id, $usu_autenticado, $fecha);
                if ($resp_estado) {
                    $exito = '1';
                }
                if ($exito) {
                    //Realizar accion                    
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Se ha eliminado el registro."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error al eliminar. "),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al realizar la acción. "),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
            }
        }
    }
}  