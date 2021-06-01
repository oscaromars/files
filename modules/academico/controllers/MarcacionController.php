<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\RegistroMarcacion;
use DateTime;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;

admision::registerTranslations();

class MarcacionController extends \app\components\CController {

    public function actionMarcacion() {
        $mod_marcacion = new RegistroMarcacion();
        $per_id = @Yii::$app->session->get("PB_perid");
        $dia = date("w", strtotime(date("Y-m-d")));
        \app\models\Utilities::putMessageLogFile('dia:' . $dia);
        $fecha_consulta = '';
        $fecha_compara = date(Yii::$app->params["dateByDefault"]);
        $cons_distancia = $mod_marcacion->consultarFechaDistancia($fecha_compara, $per_id);
        // si valor devuelve 1 existe y fecha consulta toma el valor de fecha compra
        if ($cons_distancia["existe_distancia"] > 0) {
            $fecha_consulta = $fecha_compara;
        }
        $arr_materia = $mod_marcacion->consultarMateriasMarcabyPro($per_id, $dia, $fecha_consulta);
        $arr_periodo = $mod_marcacion->consultarMateriasMarcabyPro($per_id, $dia, $fecha_consulta, true);
        return $this->render('marcacion', [
                    'model' => $arr_materia,
                    'periodo' => $arr_periodo
        ]);
    }

    public function actionIndex() {
        $mod_marcacion = new RegistroMarcacion();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademicotodos();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["materia"] = $data['materia'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["periodo"] = $data['periodo'];
            $arrSearch["estado"] = $data['estado'];
            $arr_historico = $mod_marcacion->consultarRegistroMarcacion($arrSearch);
            return $this->render('index-grid', [
                        'model' => $arr_historico,
            ]);
        } else {
            $arrSearch["periodo"] = $periodo[0]["id"];
            \app\models\Utilities::putMessageLogFile('periodo:' . $arrSearch["periodo"]);
            $arr_historico = $mod_marcacion->consultarRegistroMarcacion($arrSearch);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }        
        return $this->render('index', [
                    'arr_estado' => array("0" => Yii::t("formulario", "Todas"), "N" => Yii::t("formulario", "Sin Marcar"), "E" => Yii::t("formulario", "Sin Salida"), "S" => Yii::t("formulario", "Marcadas")),
                    'model' => $arr_historico,
                    'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $periodo), "id", "name"),
        ]);
    }

    public function actionSave() {
        $usuario = @Yii::$app->session->get("PB_iduser");
        $busqueda = 0;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $accion = $data["accion"];
            $profesor = $data["profesor"];
            $hape_id = $data["hape_id"];
            $horario = $data["horario"];
            $dia = $data["dia"];
            $fecha = date(Yii::$app->params["dateByDefault"]); // solo envia Y-m-d
            if ($accion == 'E') {
                $texto = 'entrada';
            } else {
                $texto = 'salida';
            }
            $ip = \app\models\Utilities::getClientRealIP(); // ip de la maquina
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $mod_marcacion = new RegistroMarcacion();
                // consultar si no ha guardado ya el registro de esta marcacion
                if (!empty($hape_id) && !empty($profesor) && !empty($horario) && !empty($dia) && !empty($fecha) && !empty($accion)) {
                    $cons_marcacion = $mod_marcacion->consultarMarcacionExiste($hape_id, $profesor, $fecha, $accion);
                    if (!empty($cons_marcacion["marcacion"])) {
                        $busqueda = 1;
                    }
                }
                if ($busqueda == 0) {
                    //Guardar Marcacion (iniciar (E) o finalizar (S)). 
                    if ($accion == 'E') {
                        $hora = explode("-", $horario);
                        $hora_inicio = date(Yii::$app->params["dateTimeByDefault"]);
                        $real_inicia = date(Yii::$app->params["dateByDefault"]) . ' ' . $hora[0];
                        $hora_fin = date(Yii::$app->params["dateByDefault"]) . ' ' . $hora[1];
                        $intervalo = date_diff(new DateTime($hora_inicio), new DateTime($real_inicia));
                        $horacalculada = $intervalo->format('%H:%i:%s');
                        list($horas, $minutos, $segundos) = explode(':', $horacalculada);
                        $hora_en_segundos = ($horas * 3600 ) + ($minutos * 60 ) + $segundos;
                        $minutosfinales = $hora_en_segundos / 60;
                        if (new DateTime($hora_inicio) < new DateTime($real_inicia)) {
                            $minutosfinales = $minutosfinales * -1;
                        }
                        if ($minutosfinales >= -30 && new DateTime($hora_inicio) < new DateTime($hora_fin)) { //SOLO PUEDE MARCAR 30 MINUTOS ANTES DEL INICIO Y UN 1 MINUTO ANTES DEL FINAL                            
                            $resp_marca = $mod_marcacion->insertarMarcacion($accion, $profesor, $hape_id, $hora_inicio, null, $ip, $usuario, null);
                            $rmar_idingreso = $mod_marcacion->insertarMarcacionid($resp_marca);
                            if ($resp_marca) {
                                // aqui que modifique el registro de entrada                                
                                if ($minutosfinales >= 15) { // AL MARCAR 15 MINUTOS DESPUES ENVIA MENSAJE
                                    $retraso = ', fue ' . round($minutosfinales, 0, PHP_ROUND_HALF_DOWN) . ' minutos después';
                                }
                                $exito = 1;
                            }
                        } else {
                            $exito = 0;
                            $mensaje = ' Las marcaciones solo se pueden realizar 30 minutos antes del inicio de la clase, hasta 1 minuto antes que finalice. ';
                        }
                    } else {
                        $hora = explode("-", $horario);
                        $hora_fin = date(Yii::$app->params["dateTimeByDefault"]);
                        $real_fin = date(Yii::$app->params["dateByDefault"]) . ' ' . $hora[1];
                        $intervalo = date_diff(new DateTime($hora_fin), new DateTime($real_fin));
                        $horacalculada = $intervalo->format('%H:%i:%s');
                        list($horas, $minutos, $segundos) = explode(':', $horacalculada);
                        $hora_en_segundos = ($horas * 3600 ) + ($minutos * 60 ) + $segundos;
                        $minutosfinales = $hora_en_segundos / 60;
                        if (new DateTime($hora_fin) < new DateTime($real_fin)) {
                            $minutosfinales = $minutosfinales * -1;
                        }
                        // si se quiere marcar la salida 10 minutos antes se pone $minutos finales >= -10
                        if ($minutosfinales >= -10 && $minutosfinales <= 30) { // SOLO PUEDE MARCAR SALIDA DE A LA HORA DE LA SALIDA Y HASTA 30 MINUTOS DESPUES
                            $cons_marcainicio = $mod_marcacion->consultarMarcacionExiste($hape_id, $profesor, $fecha, 'E');
                            if (!empty($cons_marcainicio["marcacion"])) {
                                $rmar_identrada = $mod_marcacion->consultarmIdMarcacion('E', $profesor, $hape_id, $fecha);                       
                                $resp_marcasa = $mod_marcacion->insertarMarcacionsalida($accion, $profesor, $hape_id, $hora_fin, $ip, $rmar_identrada["rmar_id"]);
                                $exito = 1;
                            } else {
                                $exito = 0;
                                $mensaje = ' No puede finalizar la clase si no ha iniciado la marcación';
                            }
                        } else {
                            $exito = 0;
                            $mensaje = ' Las marcaciones sólo se pueden finalizar 10 minutos antes o hasta 30 minutos después de la hora establecida. ';
                        }
                    }
                    if ($exito) {
                        $mensaje = 'Ha registrado la hora de ' . $texto . ' ' . $retraso;
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "La infomación ha sido grabada. " . $mensaje),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", $mensaje),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                    }
                } else {

                    $mensaje = 'Ya registró la ' . $texto . ' de esta materia';
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "No se puede guardar la marcación. " . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
                }
            } catch (Exception $ex) {
                $mensaje = 'Intente nuevamente';
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar." . $mensaje),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
            }
            return;
        }
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M","N","O","P");
        $arrHeader = array(
            academico::t("Academico", "Period"),
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Matter"),
            academico::t("Academico", "Modality"),
            Yii::t("formulario", "Date"),            
            academico::t("Academico", "Hour start date") . ' ' . academico::t("Academico", "Expected"),
            academico::t("Academico", "Hour end date") . ' ' . academico::t("Academico", "Expected"),
            academico::t("Academico", "Hour start date"),
            academico::t("Academico", "Hour end date"),            
            academico::t("Academico", "Hours marked"),
            academico::t("Academico", "Start IP"),
            academico::t("Academico", "End IP"),
            Yii::t("formulario", "Status"),
            ""
        );
        $mod_marcacion = new RegistroMarcacion();
        $data = Yii::$app->request->get();
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["materia"] = $data['materia'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["estado"] = $data['estado'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_marcacion->consultarRegistroMarcacion(array(), true);
        } else {
            $arrData = $mod_marcacion->consultarRegistroMarcacion($arrSearch, true);
        }
        $nameReport = academico::t("Academico", "List Bearings");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "List Bearings"); // Titulo del reporte

        $mod_marcacion = new RegistroMarcacion();
        $data = Yii::$app->request->get();
        $arr_body = array();

        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["materia"] = $data['materia'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["periodo"] = $data['periodo'];

        $arr_head = array(
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Matter"),
            Yii::t("formulario", "Date"),
            academico::t("Academico", "Hour start date"),
            academico::t("Academico", "Hour start date") . ' ' . academico::t("Academico", "Expected"),
            academico::t("Academico", "Hour end date"),
            academico::t("Academico", "Hour end date") . ' ' . academico::t("Academico", "Expected"),
            academico::t("Academico", "Start IP"),
            academico::t("Academico", "End IP"),
            Yii::t("formulario", "Period"),
            ""
        );

        if (empty($arrSearch)) {
            $arr_body = $mod_marcacion->consultarRegistroMarcacion(array(), true);
        } else {
            $arr_body = $mod_marcacion->consultarRegistroMarcacion($arrSearch, true);
        }

        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arr_head,
                    'arr_body' => $arr_body
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
        return;
    }

    public function actionCargarhorario() {
        //$per_id = @Yii::$app->session->get("PB_perid");    
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $mod_marcacion = new RegistroMarcacion();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademico();
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
                    $dirFileEnd = Yii::$app->params["documentFolder"] . "horario/" . $data["name_file"] . "." . $typeFile;
                    $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                    if ($status) {
                        return true;
                    } else {
                        return json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    }
                }
            }
            if ($data["procesar_file"]) {
                $periodo_id = $data["periodo_id"];
                \app\models\Utilities::putMessageLogFile('periodo:' . $periodo_id);
                $carga_archivo = $mod_marcacion->CargarArchivoHorario($periodo_id, $data["archivo"], $usu_id);
                if ($carga_archivo['status']) {
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Archivo procesado correctamente. " . $carga_archivo['message']),
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
        } else {
            return $this->render('cargarhorario', [
                        'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $periodo), "id", "name"),
            ]);
        }
    }

    public function actionListarhorario() {
        $mod_marcacion = new RegistroMarcacion();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademicotodos();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["periodo"] = $data['periodo'];

            $arr_horario = $mod_marcacion->consultarHorarioMarcacion($arrSearch);
            return $this->render('_listarhorario-grid', [
                        'model' => $arr_horario,
            ]);
        } else {
            $arr_horario = $mod_marcacion->consultarHorarioMarcacion();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        return $this->render('listarhorario', [
                    'model' => $arr_horario,
                    'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $periodo), "id", "name"),
                    'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
        ]);
    }

    public function actionExpexcelhorario() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        $arrHeader = array(
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Matter"),
            academico::t("Academico", "Aca. Uni."),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Class date"),
            academico::t("Academico", "Day"),
            academico::t("Academico", "Hour start date"),
            academico::t("Academico", "Hour end date"),
        );
        $mod_marcacion = new RegistroMarcacion();
        $data = Yii::$app->request->get();
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_marcacion->consultarHorarioMarcacion(array(), true);
        } else {
            $arrData = $mod_marcacion->consultarHorarioMarcacion($arrSearch, true);
        }
        $nameReport = academico::t("Academico", "List of schedules");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfhorario() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "List of schedules"); // Titulo del reporte                
        $arrHeader = array(
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Matter"),
            academico::t("Academico", "Aca. Uni."),
            academico::t("Academico", "Modality"),
            academico::t("Academico", "Class date"),
            academico::t("Academico", "Day"),
            academico::t("Academico", "Hour start date"),
            academico::t("Academico", "Hour end date"),
        );
        $mod_marcacion = new RegistroMarcacion();
        $data = Yii::$app->request->get();
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_marcacion->consultarHorarioMarcacion(array(), true);
        } else {
            $arrData = $mod_marcacion->consultarHorarioMarcacion($arrSearch, true);
        }
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

    public function actionListarnomarcadas() {
        $mod_marcacion = new RegistroMarcacion();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademico();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["materia"] = $data['materia'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["periodo"] = $data['periodo'];
            $arrSearch["tipo"] = $data['tipo'];
            $arr_historico = $mod_marcacion->consultarRegistroNoMarcacion($arrSearch, '1');
            return $this->render('_listarnomarcadas-grid', [
                        'model' => $arr_historico,
            ]);
        } else {
            //\app\models\Utilities::putMessageLogFile('no hay filtro');
            $arr_historico = $mod_marcacion->consultarRegistroNoMarcacion($arrSearch, '0');
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $arr_ninteres = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_ninteres[0]["id"], 1);
        return $this->render('listarnomarcadas', [
                    'model' => $arr_historico,
                    'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $periodo), "id", "name"),
                    'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_ninteres), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'arr_tipo' => array("E" => academico::t("Academico", "Entry"), "S" => academico::t("Academico", "Exit"))
        ]);
    }

    public function actionExpexcelnomarcadas() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
        $arrHeader = array(
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Matter"),
            academico::t("Academico", "Hour start date") . ' ' . academico::t("Academico", "Expected"),
            academico::t("Academico", "Hour end date") . ' ' . academico::t("Academico", "Expected"),
        );
        $mod_marcacion = new RegistroMarcacion();
        $data = Yii::$app->request->get();
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["materia"] = $data['materia'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["tipo"] = $data['tipo'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_marcacion->consultarRegistroNoMarcacion(array(), '0', true);
        } else {
            $arrData = $mod_marcacion->consultarRegistroNoMarcacion($arrSearch, '1', true);
        }
        if ($data["tipo"] == 'E') {
            $v_tipo = "Entrada";
        } else {
            $v_tipo = "Salida";
        }
        $nameReport = academico::t("Academico", "Teacher list unchecked") . " / " . $v_tipo;
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfnomarcadas() {
        $report = new ExportFile();
        $arrHeader = array(
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Date"),
            Yii::t("formulario", "Teacher"),
            Yii::t("formulario", "Matter"),
            academico::t("Academico", "Hour start date") . ' ' . academico::t("Academico", "Expected"),
            academico::t("Academico", "Hour end date") . ' ' . academico::t("Academico", "Expected"),
        );
        $mod_marcacion = new RegistroMarcacion();
        $data = Yii::$app->request->get();
        $arrSearch["profesor"] = $data['profesor'];
        $arrSearch["materia"] = $data['materia'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["f_ini"] = $data['f_ini'];
        $arrSearch["f_fin"] = $data['f_fin'];
        $arrSearch["periodo"] = $data['periodo'];
        $arrSearch["tipo"] = $data['tipo'];
        if ($data["tipo"] == 'E') {
            $v_tipo = "Entrada";
        } else {
            $v_tipo = "Salida";
        }
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $mod_marcacion->consultarRegistroNoMarcacion(array(), '0', true);
        } else {
            $arrData = $mod_marcacion->consultarRegistroNoMarcacion($arrSearch, '1', true);
        }
        $this->view->title = academico::t("Academico", "Teacher list unchecked") . " / " . $v_tipo; // Titulo del reporte                
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

}
