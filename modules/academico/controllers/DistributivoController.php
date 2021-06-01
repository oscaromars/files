<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\Asignatura;
use app\modules\academico\models\Distributivo;
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

academico::registerTranslations();
admision::registerTranslations();

class DistributivoController extends \app\components\CController {

    public function actionIndex() {
        $distributivo_model = new Distributivo();
        $mod_semestre = new SemestreAcademico();
        $mod_unidad = new UnidadAcademica();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["semestre"] = $data['semestre'];
            $model = $distributivo_model->consultarDistributivo($arrSearch);
            return $this->render('index-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $distributivo_model->consultarDistributivo();
        }
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_semestre = $mod_semestre->consultarSemestres();
        return $this->render('index', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_semestre' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_semestre), "id", "name"),
                    'model' => $distributivo_model->consultarDistributivo(),
        ]);
    }

    public function actionExpexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            academico::t("Academico", "Teacher"),
            academico::t("Academico", "Dedication"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Description"),
            Yii::t("formulario", "Semester"),
        );
        $distributivo_model = new Distributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["semestre"] = $data['semestre'];
        $arrData = array();
        if ($arrSearch["unidad"] == 0 and $arrSearch["semestre"] == 0 and ( empty($arrSearch["search"]))) {
            \app\models\Utilities::putMessageLogFile('arrSearch vacío');
            $arrData = $distributivo_model->consultarDistributivoReporte(array());
        } else {
            $arrData = $distributivo_model->consultarDistributivoReporte($arrSearch);
        }
        $nameReport = academico::t("Academico", "Distributive List");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Distributive List"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            academico::t("Academico", "Teacher"),
            academico::t("Academico", "Dedication"),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Description"),
            Yii::t("formulario", "Semester"),
        );
        $distributivo_model = new Distributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["semestre"] = $data['semestre'];
        $arrData = array();
        if ($arrSearch["unidad"] == 0 and $arrSearch["semestre"] == 0 and ( empty($arrSearch["search"]))) {
            $arrData = $distributivo_model->consultarDistributivoReporte(array());
        } else {
            $arrData = $distributivo_model->consultarDistributivoReporte($arrSearch);
        }
        $report->orientation = "L"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionCarga_horaria() {
        $distributivo_model = new Distributivo();
        $mod_semestre = new SemestreAcademico();
        $mod_tipo = new TipoDistributivo();
        $data = Yii::$app->request->get();
        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["tipo"] = $data['tipo'];
            $arrSearch["semestre"] = $data['semestre'];
            $model = $distributivo_model->consultarCargaHoraria($arrSearch);
            return $this->render('carga_horaria-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $distributivo_model->consultarCargaHoraria();
        }
        $arr_tipo = $mod_tipo->consultarTipoDistributivo();
        $arr_semestre = $mod_semestre->consultarSemestres();
        return $this->render('carga_horaria', [
                    'mod_tipo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_tipo), "id", "name"),
                    'mod_semestre' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_semestre), "id", "name"),
                    'model' => $distributivo_model->consultarCargaHoraria(),
        ]);
    }

    public function actionExpexcelhoras() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
        $arrHeader = array(
            Yii::t("formulario", "DNI 1"),
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "Semester"),
            academico::t("Academico", "Teaching"),
            academico::t("Academico", "Tutorial"),
            academico::t("Academico", "Investigation"),
            academico::t("Academico", "Bonding"),
            academico::t("Academico", "Administrative"),
            academico::t("Academico", "Other activities"),
            academico::t("Academico", "Total Hours")
        );
        $distributivo_model = new Distributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["tipo"] = $data['tipo'];
        $arrSearch["semestre"] = $data['semestre'];
        $arrData = array();
        if ($arrSearch["tipo"] == 0 and $arrSearch["semestre"] == 0 and ( empty($arrSearch["search"]))) {
            \app\models\Utilities::putMessageLogFile('arrSearch vacío');
            $arrData = $distributivo_model->consultarCargaHorariaReporte(array());
        } else {
            $arrData = $distributivo_model->consultarCargaHorariaReporte($arrSearch);
        }
        $nameReport = academico::t("Academico", "Workload");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionListarestudiantes() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $distributivo_model = new Distributivo();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["periodo"] = $data['periodo'];
            if($data['estado'] == '1')
            {
                $estadopago = 'C';
            } 
            if($data['estado'] == '0')
            {
                $estadopago = 'N';
            }  
            $arrSearch["estado"] = $estadopago;            
            $arrSearch["jornada"] = $data['jornada'];
            $model = $distributivo_model->consultarDistributivoxProfesor($arrSearch, $per_id, 1);
            return $this->render('listar_distributivo-grid', [
                        "model" => $model,
            ]);
        } else {
            $arrSearch["periodo"] = 7;
            $model = $distributivo_model->consultarDistributivoxProfesor($arrSearch, $per_id, 1);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uaca_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[0]["id"], 1);
        $arr_periodo = $mod_periodo->consultarPeriodoAcademico();
        return $this->render('listar_distributivo_profesor', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    'mod_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
                    'mod_estado' => ArrayHelper::map(array_merge([["id" => "2", "name" => Yii::t("formulario", "Grid")]], [["id" => "0", "name" => Yii::t("formulario", "No Autorizado")]], [["id" => "1", "name" => Yii::t("formulario", "Autorizado")]]), "id", "name"),
                    'model' => $model,
                    'mod_jornada' => array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia"),
        ]);
    }

    public function actionExpexceldist() {
        $per_id = @Yii::$app->session->get("PB_perid");

        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
        $arrHeader = array(
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Complete Names"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Payment Status"),
        );
        \app\models\Utilities::putMessageLogFile('perid:' . $per_id);
        $distributivo_model = new Distributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        //$arrSearch["estado"] = $data['estado'];
        if($data['estado'] == '1')
            {
                $estadopago = 'C';
            } 
            if($data['estado'] == '0')
            {
                $estadopago = 'N';
            }  
            $arrSearch["estado"] = $estadopago;
        $arrData = array();
        if ($arrSearch["unidad"] == 0 and $arrSearch["modalidad"] == 0 and $arrSearch["periodo"] == 0 and $arrSearch["estado"] == 2 and ( empty($arrSearch["search"]))) {
            $arrData = $distributivo_model->consultarDistributivoxProfesor(array(), $per_id, 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxProfesor($arrSearch, $per_id, 0);
        }
        $nameReport = academico::t("Academico", "Listado de estudiantes");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfdis() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de estudiantes"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Complete Names"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Subject"),
            Yii::t("formulario", "Payment Status"),
        );
        $distributivo_model = new Distributivo();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = $data['search'];
        $arrSearch["unidad"] = $data['unidad'];
        $arrSearch["modalidad"] = $data['modalidad'];
        $arrSearch["periodo"] = $data['periodo'];
        //$arrSearch["estado"] = $data['estado'];
        if($data['estado'] == '1')
            {
                $estadopago = 'C';
            } 
            if($data['estado'] == '0')
            {
                $estadopago = 'N';
            }  
            $arrSearch["estado"] = $estadopago;
            
        $arrData = array();
        if ($arrSearch["unidad"] == 0 and $arrSearch["modalidad"] == 0 and $arrSearch["periodo"] == 0 and $arrSearch["estado"] == 2 and ( empty($arrSearch["search"]))) {
            $arrData = $distributivo_model->consultarDistributivoxProfesor(array(), $per_id, 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxProfesor($arrSearch, $per_id, 0);
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

    public function actionListarestudiantespago() {
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
            $arrSearch["jornada"] = $data['jornada'];
            $model = $distributivo_model->consultarDistributivoxEstudiante($arrSearch, 1);
            return $this->render('_listarestudiantespagogrid', [
                        "model" => $model,
            ]);
        } else {
            //$arrSearch["periodo"] = 8;
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
        return $this->render('listarestudiantepago', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    "mod_periodo" => ArrayHelper::map($arr_periodo, "id", "name"),
                    'mod_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_asignatura), "id", "name"),
                    'model' => $model,
                    //'mod_estado' => array("-1" => "Todos", "null" => "Pendiente", "0" => "Deuda", "1" => "Pagado"),
                    'mod_estado' => array("-1" => "Todos", "0" => "No Autorizado", "1" => "Autorizado"),
                    'mod_jornada' => array("0" => "Todos", "1" => "(M) Matutino", "2" => "(N) Nocturno", "3" => "(S) Semipresencial", "4" => "(D) Distancia"),
        ]);
    }

    public function actionSavestudiantespago() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $periodo = $data["periodo"];
            $pagado = $data["pagado"];
            $nopagado = $data["nopagado"];

            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if (!empty($pagado)) {
                    $pagados = explode(",", $pagado); //PAGADOS
                    foreach ($pagados as $est_id) {  // empieza foreach para guardar los pagados
                        //Verificar que no haya guardado el estudiante en el periodo y est_id para insert, si guardo es update PAGADOS.
                        // en un ciclo primero los pagados, luego los no pagado
                        $distributivo_model = new Distributivo();
                        $resp_consPeriodopago = $distributivo_model->consultarPeriodopago($periodo, null, $est_id);
                        if (!empty($resp_consPeriodopago["eppa_id"])) {
                            // update pagados   
                            $resp_guardapago = $distributivo_model->modificarPagoestudiante($periodo, null, $est_id, 1, $usu_id);
                            $exito = 1;
                        } else {
                            // es un insert pagados
                            $resp_modificarpago = $distributivo_model->insertarPagoestudiante($periodo, null, $est_id, 1, $usu_id);
                            $exito = 1;
                        }
                    } // cierra foreach 
                }
                if (!empty($nopagado)) {
                    $nopagados = explode(",", $nopagado); //NO PAGADOS
                    foreach ($nopagados as $est_id) {  // empieza foreach para guardar los no pagados
                        //Verificar que no haya guardado el estudiante en el periodo y est_id para insert, si guardo es update NO PAGADOS.                    
                        $distributivo_model = new Distributivo();
                        $resp_consPeriodonopago = $distributivo_model->consultarPeriodopago($periodo, null, $est_id);
                        if (!empty($resp_consPeriodonopago["eppa_id"])) {
                            // update NO pagados 
                            $resp_guardanopago = $distributivo_model->modificarPagoestudiante($periodo, null, $est_id, 0, $usu_id);
                            $exito = 1;
                        } else {
                            // es un insert NO pagados
                            $resp_modificarnopago = $distributivo_model->insertarPagoestudiante($periodo, null, $est_id, 0, $usu_id);
                            $exito = 1;
                        }
                    } // cierra foreach 
                }
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

    public function actionExpexcelestpago() {
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
        $arrSearch["jornada"] = $data['jornada'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $distributivo_model->consultarDistributivoxEstudiante(array(), 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxEstudiante($arrSearch, 0);
        }
        $nameReport = academico::t("Academico", "Listado de estudiantes pago");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfestpago() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de estudiantes pago"); // Titulo del reporte
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
        $arrSearch["jornada"] = $data['jornada'];
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

    public function actionListarestudiantespagopos() {
        //$per_id = @Yii::$app->session->get("PB_perid");
        $distributivo_model = new Distributivo();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_promocion = new PromocionPrograma();
        $mod_paralelo = new ParaleloPromocionPrograma();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $arrSearch["search"] = $data['search'];
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["promocion"] = $data['promocion'];
            $arrSearch["asignatura"] = $data['asignatura'];
            $arrSearch["estado_pago"] = $data['estado'];
            $arrSearch["paralelo"] = $data['paralelo'];
            $model = $distributivo_model->consultarDistributivoxEstudiantepos($arrSearch, 1);
            return $this->render('_listarestudiantespagoposgrid', [
                        "model" => $model,
            ]);
        } else {
            $model = $distributivo_model->consultarDistributivoxEstudiantepos(null, 1);
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
            if (isset($data["getparalelo"])) {
                $paralelo = $mod_paralelo->consultarParalelosxPrograma($data["promo_id"]);
                $message = array("paralelo" => $paralelo);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_unidad[1]["id"], 1);
        $arr_promocion = $mod_promocion->consultarPromocionxProgramagen();
        $arr_Paralelos = $mod_paralelo->consultarParalelosxPrograma($arr_promocion[0]["id"]);
        $arr_asignatura = $distributivo_model->consultarAsiganturaxuniymoda(0, 0);
        return $this->render('listarestudiantepagopos', [
                    'mod_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_unidad), "id", "name"),
                    'mod_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_modalidad), "id", "name"),
                    "mod_promocion" => ArrayHelper::map($arr_promocion, "id", "name"),
                    'mod_asignatura' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_asignatura), "id", "name"),
                    'model' => $model,
                    'mod_estado' => array("-1" => "Todos", "0" => "No Autorizado", "1" => "Autorizado"),
                    'mod_paralelo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_Paralelos), "id", "name"),]);
    }

    public function actionExpexcelestpagopos() {
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
            academico::t("Academico", "Promotion"),
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
        $arrSearch["promocion"] = $data['promocion'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["estado_pago"] = $data['estado'];
        $arrSearch["paralelo"] = $data['paralelo'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $distributivo_model->consultarDistributivoxEstudiantepos(array(), 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxEstudiantepos($arrSearch, 0);
        }
        $nameReport = academico::t("Academico", "Listado de estudiantes pago posgrado");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExppdfestpagopos() {
        $report = new ExportFile();
        $this->view->title = academico::t("Academico", "Listado de estudiantes pago posgrado"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", " "),
            Yii::t("formulario", "Academic unit"),
            Yii::t("formulario", "Mode"),
            Yii::t("formulario", "DNI"),
            Yii::t("formulario", "Complete Names"),
            academico::t("Academico", "Promotion"),
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
        $arrSearch["promocion"] = $data['promocion'];
        $arrSearch["asignatura"] = $data['asignatura'];
        $arrSearch["estado_pago"] = $data['estado'];
        $arrSearch["paralelo"] = $data['paralelo'];
        $arrData = array();
        if (empty($arrSearch)) {
            $arrData = $distributivo_model->consultarDistributivoxEstudiantepos(array(), 0);
        } else {
            $arrData = $distributivo_model->consultarDistributivoxEstudiantepos($arrSearch, 0);
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

// OJO REPLICAR LA MISMA FUNCION LUEGO DE MODIIFCAR LAS FUNCIONES PARA POSGRADO NOMGRE DE FUNCION ==>Savestudiantespagopos
    public function actionSavestudiantespagopos() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $promocion = $data["promocion"];
            $pagado = $data["pagado"];
            $nopagado = $data["nopagado"];

            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if (!empty($pagado)) {
                    $pagados = explode(",", $pagado); //PAGADOS
                    foreach ($pagados as $est_id) {  // empieza foreach para guardar los pagados
                        //Verificar que no haya guardado el estudiante en el periodo y est_id para insert, si guardo es update PAGADOS.
                        // en un ciclo primero los pagados, luego los no pagado
                        $distributivo_model = new Distributivo();
                        // OJO MODIFICAR Y PROBAR EN POSGRADO Y GRADO QUE CONSULTE SEGUN PERIODO O PROMOCION
                        $resp_consPeriodopago = $distributivo_model->consultarPeriodopago(null, $promocion, $est_id);
                        if (!empty($resp_consPeriodopago["eppa_id"])) {
                            // update update pagados   
                            // OJO MODIFICAR Y PROBAR EN POSGRADO Y GRADO QUE MODIFIQUE SEGUN PERIODO O PROMOCION
                            $resp_guardapago = $distributivo_model->modificarPagoestudiante(null, $promocion, $est_id, 1, $usu_id);
                            $exito = 1;
                        } else {
                            // es un insert pagados
                            // OJO MODIFICAR Y PROBAR EN POSGRADO Y GRADO QUE INGRESE SEGUN PERIODO O PROMOCION
                            $resp_modificarpago = $distributivo_model->insertarPagoestudiante(null, $promocion, $est_id, 1, $usu_id);
                            $exito = 1;
                        }
                    } // cierra foreach 
                }
                if (!empty($nopagado)) {
                    $nopagados = explode(",", $nopagado); //NO PAGADOS
                    foreach ($nopagados as $est_id) {  // empieza foreach para guardar los no pagados
                        //Verificar que no haya guardado el estudiante en el periodo y est_id para insert, si guardo es update NO PAGADOS.                    
                        $distributivo_model = new Distributivo();
                        // OJO MODIFICAR Y PROBAR EN POSGRADO Y GRADO QUE CONSULTE SEGUN PERIODO O PROMOCION
                        $resp_consPeriodonopago = $distributivo_model->consultarPeriodopago(null, $promocion, $est_id);
                        if (!empty($resp_consPeriodonopago["eppa_id"])) {
                            // update update NO pagados 
                            // OJO MODIFICAR Y PROBAR EN POSGRADO Y GRADO QUE MODIFIQUE SEGUN PERIODO O PROMOCION
                            $resp_guardanopago = $distributivo_model->modificarPagoestudiante(null, $promocion, $est_id, 0, $usu_id);
                            $exito = 1;
                        } else {
                            // es un insert NO pagados
                            // OJO MODIFICAR Y PROBAR EN POSGRADO Y GRADO QUE INGRESE SEGUN PERIODO O PROMOCION
                            $resp_modificarnopago = $distributivo_model->insertarPagoestudiante(null, $promocion, $est_id, 0, $usu_id);
                            $exito = 1;
                        }
                    } // cierra foreach 
                }
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

}
