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
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\Distributivo;
use app\modules\academico\models\PeriodoAcademico;
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
                //\app\models\Utilities::putMessageLogFile('Files controller ...: ' . $data["archivo"]);
                $carga_archivo = $mod_educativa->CargarArchivoeducativa($data["archivo"]);
                if ($carga_archivo['status']) {
                    //\app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $arroout['noalumno']);
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
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["periodo"] = $data['periodo'];
            $arrSearch["asignatura"] = $data['asignatura'];
            $arrSearch["estado_pago"] = $data['estado'];
            //$arrSearch["jornada"] = $data['jornada'];
            $model = $distributivo_model->consultarDistributivoxEstudiante($arrSearch, 1);
            return $this->render('_listarestudiantesregistrogrid', [
                        "model" => $model,
            ]);
        } else {
            $model = $distributivo_model->consultarDistributivoxEstudiante(null, 1);
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
        }
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        $arr_periodo = $mod_periodo->consultarPeriodoAcademicotodos();
        $arr_asignatura = $distributivo_model->consultarAsiganturaxuniymoda(0, 0);
        return $this->render('listarestudianteregistro', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    "mod_periodo" => ArrayHelper::map($arr_periodo, "id", "name"),
                    'mod_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_asignatura), "id", "name"),
                    'model' => $model,
                     'mod_estado' => array("-1" => "Todos", "0" => "No Autorizado", "1" => "Autorizado"),
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
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
        $arrHeader = array(
            Yii::t("formulario", " "),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Complete Names"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Payment Status"),
            Yii::t("formulario", "Date"),
        );
        //\app\models\Utilities::putMessageLogFile('perid:' . $per_id);
        $distributivo_model = new Distributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["estado_pago"] = $data['estado'];
        //$arrSearch["jornada"] = $data['jornada'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $distributivo_model->consultarDistributivoxEstudiante(array(), 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxEstudiante($arrSearch, 0);
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
            Yii::t("formulario", " "),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Complete Names"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Payment Status"),
            Yii::t("formulario", "Date"),
        );
        $distributivo_model = new Distributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["estado_pago"] = $data['estado'];
        //$arrSearch["jornada"] = $data['jornada'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $distributivo_model->consultarDistributivoxEstudiante(array(), 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxEstudiante($arrSearch, 0);
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
                        "wtmessage" => Yii::t("notificaciones", "Error al procesar el archivo1. " . $carga_archivo['message']),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
                return;
            } catch (Exception $ex) {      
                $message = array(
                    'wtmessage' => Yii::t('notificaciones', 'Error al procesar el archivo2.'),
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
            //$cedu_id = 1;
            // consultar la infomracion del curso por cedu_id
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
                            "wtmessage" => Yii::t("notificaciones", "Error al grabar1." . $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), false, $message);
                    }             
                
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar2."),
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
                \app\models\Utilities::putMessageLogFile('curso id..: ' . $cur_id);     
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

}  