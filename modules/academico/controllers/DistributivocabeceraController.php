<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\DistributivoCabecera;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\TipoDistributivo;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\models\ExportFile;
use app\modules\academico\models\Profesor;
use Exception;

academico::registerTranslations();
admision::registerTranslations();

class DistributivocabeceraController extends \app\components\CController {

    public $pdf_cla_acceso = "";

    private function estados() {
        return [
            '-1' => Yii::t("formulario", "Todos"),
            '1' => Yii::t("formulario", "Revisado"),
            '2' => Yii::t("formulario", "Aprobado"),
            '0' => Yii::t("formulario", "No aprobado"),
        ];
    }

     private function estadoRevis() {
        return [
            '0' => Yii::t("formulario", "Selecionar"),
            '1' => Yii::t("formulario", "Revisado"),

        ];
    }

    private function estadoRevision() {
        return [
            '0' => Yii::t("formulario", "Seleccionar"),
            '2' => Yii::t("formulario", "APPROVED"),
            '3' => Yii::t("formulario", "Not approved"),
        ];
    }

    private function estadoReversar() {
        return [
            '0' => Yii::t("formulario", "Seleccionar"),
            '4' => Yii::t("formulario", "Reversado"),
        ];
    }

      public function actionAprobardistributivo() {
         $searchModel = new \app\modules\academico\models\DistributivoCabeceraSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

              return $this->render('aprobardistributivo', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
      }
    public function actionIndex() {
        $per_id = @Yii::$app->session->get("PB_perid");
        $model = NULL;
        $distributivocab_model = new DistributivoCabecera();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_tipo_distributivo = new TipoDistributivo();
        $mod_profesor = new Profesor();
        $data = Yii::$app->request->get();

        if ($data['PBgetFilter']) {
            $search = $data['search'];
            $periodo = (isset($data['periodo']) && $data['periodo'] > 0) ? $data['periodo'] : NULL;
            $estado = (isset($data['estado']) && $data['estado'] > -1) ? $data['estado'] : NULL;
            $profesor = (isset($data['profesor']) && $data['profesor'] > 0) ? $data['profesor'] : NULL;
            $asignacion = (isset($data['asignacion']) && $data['asignacion'] > 0) ? $data['asignacion'] : NULL;

            $model = $distributivocab_model->getListadoDistributivoCab($search, $periodo, $estado, $asignacion, $profesor);
            return $this->render('index-grid', [
                        "model" => $model,
            ]);
        } else {
            $model = $distributivocab_model->getListadoDistributivoCab();
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }
        $arr_periodo = $mod_periodo->consultarPeriodoAcademico();
        $arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo();
        $arr_profesor = $mod_profesor->getProfesores();
        return $this->render('index', [
                    'mod_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
                    'mod_tipo_distributivo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_tipo_distributivo), "id", "name"),
                    'arrEstados' => $this->estados(),
                    'model' => $model,
                    'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
        ]);
    }

      public function actionDetalledistributivo() {
          $detalle="prueba";
           return $this->render('index', ['detalle'=>$detalle]);
      }
    
    
    public function actionExportexcel() {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O");
        $arrHeader = array(
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Status"),
            Yii::t("formulario", "Tipo_Asignacion"),
            Yii::t("formulario", "UnidadAcademica"),
            Yii::t("formulario", "Modalidad"),
            Yii::t("formulario", "Asignatura"),
            Yii::t("formulario", "Horario"),
        );
        $distributivocab_model = new DistributivoCabecera();
        $data = Yii::$app->request->get();

        $arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0) ? $data['periodo'] : NULL;
        $arrSearch["estado"] = ($data['estado'] > 0) ? $data['estado'] : NULL;
        $arrSearch["estado"] = ($data['estado'] > 0) ? $data['estado'] : NULL;
        $arrSearch["asignacion"] = ($data['asignacion'] > 0) ? $data['asignacion'] : NULL;

        $arrData = $distributivocab_model->getListadoDistributivoCab($arrSearch["search"], $arrSearch["periodo"], $arrSearch["estado"], $arrSearch["asignacion"], true);

        foreach ($arrData as $key => $value) {
            unset($arrData[$key]["Id"]);
        }
        $nameReport = academico::t("distributivoacademico", "List of Distributive Teachers");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExportpdf() {
        $report = new ExportFile();
        $this->view->title = academico::t("distributivoacademico", "List of Distributive Teachers"); // Titulo del reporte
        $arrHeader = array(
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "DNI 1"),
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "Status"),
            Yii::t("formulario", "Tipo_Asignacion"),
            Yii::t("formulario", "UnidadAcademica"),
            Yii::t("formulario", "Modalidad"),
            Yii::t("formulario", "Asignatura"),
            Yii::t("formulario", "Horario"),
        );
        $distributivocab_model = new DistributivoCabecera();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0) ? $data['periodo'] : NULL;
        $arrSearch["estado"] = ($data['estado'] > 0) ? $data['estado'] : NULL;
        $arrSearch["asignacion"] = ($data['asignacion'] > 0) ? $data['asignacion'] : NULL;

        $arrData = $distributivocab_model->getListadoDistributivoCab($arrSearch["search"], $arrSearch["periodo"], $arrSearch["estado"], $arrSearch["asignacion"], true);
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
                $this->render('exportpdf', [
                    'arr_head' => $arrHeader,
                    'arr_body' => $arrData,
                ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionDeletecab() {
        $distributivo_model = new DistributivoAcademico();
        $distributivo_cab = new DistributivoCabecera();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id = $data["id"];
            $resCab = $distributivo_cab->obtenerDatosCabecera($id);

            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if ($resCab["estado"] != 2) {
                    $resInactCab = $distributivo_cab->inactivarDistributivoCabecera($id);
                    //  \app\models\Utilities::putMessageLogFile('$resInactCab:'.$resInactCab);            
                    if ($resInactCab) {
                        // Inactivar distributivo académico
                        $resInactiva = $distributivo_model->inactivarDistributivoAcademico($resCab["pro_id"], $resCab["paca_id"]);
                        if ($resInactiva) {
                            $transaction->commit();
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'true', $message);
                        } else {
                            $transaction->rollback();
                            $message = array(
                                "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                                "title" => Yii::t('jslang', 'Error'),
                            );
                            return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                        }
                    }
                } else {  //Tiene estado aprobado
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Imposible eliminar el distributivo, porque se encuentra aprobado.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionReview() {
        $pro_id = 0;
        $paca_id = 0;
        $promajustado = 0;
        $DistADO = new DistributivoCabecera();
        $param = Yii::$app->request->queryParams;
        if ($param) {
            $pro_id = $param['cmb_profesor'];
            $paca_id = $param['cmb_periodo'];
        }

        $distributivo_model = new DistributivoAcademico();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $arr_periodo = $mod_periodo->consultarPeriodoAcademico();
        $distributivo_cab = new DistributivoCabecera();
        $mod_profesor = new Profesor();
        $model = new \app\modules\academico\models\DistributivoCabeceraSearch();
        $arr_profesor = $mod_profesor->getProfesoresDistributivo();
        $resCab = $distributivo_cab->obtenerDatoCabecera($pro_id, $paca_id);
        $arr_distributivo = $distributivo_model->getListarReview($resCab['dcab_id']);
        if(!empty($resCab['dcab_id']))
        {
            \app\models\Utilities::putMessageLogFile('dcab_id: ' . $resCab['dcab_id']);
            $valores_promedio =$DistADO->promedio($resCab['dcab_id']);
            $valores_promedio[0]['preparacion_docencia'] = /*(( $valores_promedio[0]['total_hora_semana_docencia_prese'] + $valores_promedio[0]['total_hora_semana_docencia_online']) **/ 0.30/*)*/;
            $valores_promedio[0]['total_hora_semana_docencia'] = $valores_promedio[0]['total_hora_semana_docencia_prese'] + $valores_promedio[0]['total_hora_semana_docencia_online'];
            //$total_hora_semana_docenciaposgrado = $valores_promedio[0]['total_hora_semana_docencia_posgrado'];
            \app\models\Utilities::putMessageLogFile('dcab_id: ' . $resCab['dcab_id']);
            \app\models\Utilities::putMessageLogFile('total_hora_semana_docencia_prese: ' . $valores_promedio[0]['total_hora_semana_docencia_prese']);
            \app\models\Utilities::putMessageLogFile('total_hora_semana_docencia_online: ' . $valores_promedio[0]['total_hora_semana_docencia_online']);
            \app\models\Utilities::putMessageLogFile('total_hora_semana_docencia: ' . $valores_promedio[0]['total_hora_semana_docencia']);
            \app\models\Utilities::putMessageLogFile('total_hora_semana_investigacion: ' . $valores_promedio[0]['total_hora_semana_investigacion']);
            \app\models\Utilities::putMessageLogFile('total_hora_semana_vinculacion: ' . $valores_promedio[0]['total_hora_semana_vinculacion']);
            Utilities::putMessageLogFile('$total_hora_semana_docenciaposgrado ' . $valores_promedio[0]['total_hora_semana_docencia_posgrado'] );
            \app\models\Utilities::putMessageLogFile('preparacion_docencia: ' . $valores_promedio[0]['preparacion_docencia']);
            \app\models\Utilities::putMessageLogFile('semanas_docencia: ' . $valores_promedio[0]['semanas_docencia']);
            \app\models\Utilities::putMessageLogFile('semanas_tutoria_vinulacion_investigacion: ' . $valores_promedio[0]['semanas_tutoria_vinulacion_investigacion']);
            Utilities::putMessageLogFile('$semanas_posgrado ' . $valores_promedio[0]['semanas_posgrado']);
        $promajustado = $DistADO->Calcularpromedioajustado($resCab['dcab_id'], /*$valores_promedio[0]['total_hora_semana_docencia_posgrado'],*/ $valores_promedio[0]['total_hora_semana_docencia'], $valores_promedio[0]['total_hora_semana_tutoria'], $valores_promedio[0]['total_hora_semana_investigacion'], $valores_promedio[0]['total_hora_semana_vinculacion'], $valores_promedio[0]['preparacion_docencia'], $valores_promedio[0]['semanas_docencia'], $valores_promedio[0]['semanas_tutoria_vinulacion_investigacion']/*, $valores_promedio[0]['semanas_posgrado']*/);
        }
        return $this->render('review',
                        ['model' => $model,
                         'arr_profesor' => ArrayHelper::map(array_merge([["Id" => "0", "Nombres" => Yii::t("formulario", "Select")]], $arr_profesor), "Id", "Nombres"),
                         'arr_detalle' => $arr_distributivo,
                         'resCab' => $resCab,
                         'mod_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Grid")]], $arr_periodo), "id", "name"),
                         'arr_estado' => $this->estados(),
                         'promajustado' => $promajustado,
        ]);
    }

    public function actionReversar($id) {
        $distributivo_model = new DistributivoAcademico();
        $distributivo_cab = new DistributivoCabecera();
        $resCab = $distributivo_cab->obtenerDatosCabecera($id);
        $arr_distributivo = $distributivo_model->getListarDistribProf($id);
        return $this->render('reversar', [
                    'arr_cabecera' => $resCab,
                    'arr_detalle' => $arr_distributivo,
                    'arr_estado' => $this->estadoReversar(),
        ]);
    }

    
    public function actionAprobar() {
        $distributivo_cab = new DistributivoCabecera();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id = $data["id"];
            $estado = $data["resultado"];
            $observacion = $data["observacion"];
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if ($estado != 0) {
                    if ($estado == 3 && empty($observacion)) {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Digite una observación de la Revisión.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                    $resultado = $distributivo_cab->aprobarDistributivo($id, $estado, ucfirst(strtolower($observacion)));
                    //  \app\models\Utilities::putMessageLogFile('resultadoREV:'.$resultado);            
                    if ($resultado) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'true', $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Seleccione un estado de Revisión.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    
    
    public function actionSavereview() {
        $distributivo_cab = new DistributivoCabecera();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id = $data["id"];
            $estado = $data["resultado"];
            $observacion = $data["observacion"];
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if ($estado != 0) {
                    if ($estado == 3 && empty($observacion)) {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Digite una observación de la Revisión.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                    $resultado = $distributivo_cab->revisarDistributivo($id, $estado, ucfirst(strtolower($observacion)));
                    //  \app\models\Utilities::putMessageLogFile('resultadoREV:'.$resultado);            
                    if ($resultado) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'true', $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Seleccione un estado de Revisión.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionSavereversar() {
        $distributivo_cab = new DistributivoCabecera();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $id = $data["id"];
            $estado = $data["resultado"];
            $observacion = $data["observacion"];
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                if ($estado != 0) {
                    if ($estado == 3 && empty($observacion)) {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Digite una observación de la Revisión.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                    $resultado = $distributivo_cab->reversarDistributivo($id, $estado, ucfirst(strtolower($observacion)));
                    //  \app\models\Utilities::putMessageLogFile('resultadoREV:'.$resultado);            
                    if ($resultado) {
                        $transaction->commit();
                        $message = array(
                            "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'true', $message);
                    } else {
                        $transaction->rollback();
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t('notificaciones', 'Seleccione un estado de Revisión.'),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionValidadistributivo() {
        $distributivo_cab = new DistributivoCabecera();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $pro_id = $data["pro_id"];
            $paca_id = $data["paca_id"];
            $tran_id = $data["transaccion"];
             $mod_tipo_distributivo = new TipoDistributivo();
            //\app\models\Utilities::putMessageLogFile('profesor:'.$pro_id);   
            //if ($pro_id !=0) {
            if (isset($data["getvalida"])) {
                $resp = $distributivo_cab->existeDistCabecera($data["paca_id"], $data["pro_id"]);
                if ($data["transaccion"] == "N") {
                    if (($resp != 0)) {
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Profesor ya tiene distributivo creado para este período académico.'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    } else {
                         $profesor_model = Profesor::findOne(['pro_id' => $pro_id]);
                          $arr_tipo_distributivo = $mod_tipo_distributivo->consultarTipoDistributivo($profesor_model->ddoc_id);
                  
                        $message = array(
                                       'asignacion' => $arr_tipo_distributivo,
                        );
                              return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                              // return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                    }
                } else {
                    if ($resp["dcab_estado_revision"] == 2) {
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'Distributivo ya se encuentra aprobado, imposible realizar cambios'),
                            "title" => Yii::t('jslang', 'Error'),
                        );
                        return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    } else {
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'OK'),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
                    }
                }
            }
        }
    }

    public function actionGenerarmateriacarga($ids) {
        //try {

        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        $rep = new ExportFile();
        //$this->layout = false;
        $this->layout = '@modules/academico/views/tpl_asignamaterias/main';
        //$this->view->title = "Invoices";
        $model_distacade = new DistributivoAcademico();
        $DistADO = new DistributivoCabecera();
        $cabDist = $DistADO->consultarCabDistributivo($ids);
        $valores_promedio =$DistADO->promedio($ids);
        $valores_promedio[0]['preparacion_docencia'] = /*(( $valores_promedio[0]['total_hora_semana_docencia_prese'] + $valores_promedio[0]['total_hora_semana_docencia_online']) **/ 0.30/*)*/;
        $valores_promedio[0]['total_hora_semana_docencia'] = $valores_promedio[0]['total_hora_semana_docencia_prese'] + $valores_promedio[0]['total_hora_semana_docencia_online'];
        $horas_carga_docente_bloquegrado = ($valores_promedio[0]['semanas_docencia'] * $valores_promedio[0]['total_hora_semana_docencia']);
        Utilities::putMessageLogFile('$horas_carga_docente_bloquegrado ' . $horas_carga_docente_bloquegrado);
        /********************************************************************************************************************************************************************************************* */
        $posgrado = $model_distacade->getSemanahoraposgrado($ids);
        for ($i=0;$i < count($posgrado); $i++){
          $horas_carga_docente_bloqueposgrado += ($posgrado[$i]['semanas_posgrado'] * $posgrado[$i]['total_hora_semana_docenciaposgrado']);
        }
        Utilities::putMessageLogFile('$horas_carga_docente_bloqueposgrado ' . $horas_carga_docente_bloqueposgrado);
        $horas_carga_docente_bloque = $horas_carga_docente_bloquegrado + $horas_carga_docente_bloqueposgrado;
        Utilities::putMessageLogFile('$horas_carga_docente_bloque ' . $horas_carga_docente_bloque);
        $promedio = $DistADO->Calcularpromedioajustado($ids, /*$valores_promedio[0]['total_hora_semana_docencia_posgrado'],*/ $valores_promedio[0]['total_hora_semana_docencia'], $valores_promedio[0]['total_hora_semana_tutoria'], $valores_promedio[0]['total_hora_semana_investigacion'], $valores_promedio[0]['total_hora_semana_vinculacion'], $valores_promedio[0]['preparacion_docencia'], $valores_promedio[0]['semanas_docencia'], $valores_promedio[0]['semanas_tutoria_vinulacion_investigacion']/*, $valores_promedio[0]['semanas_posgrado']*/);
        /*Utilities::putMessageLogFile('$total_hora_semana_docencia ' . $valores_promedio[0]['total_hora_semana_docencia'] );
        Utilities::putMessageLogFile('$total_hora_semana_tutoria ' . $valores_promedio[0]['total_hora_semana_tutoria']);
        Utilities::putMessageLogFile('$total_hora_semana_investigacion ' . $valores_promedio[0]['total_hora_semana_investigacion'] );
        Utilities::putMessageLogFile('$total_hora_semana_vinculacion ' . $valores_promedio[0]['total_hora_semana_vinculacion'] );
        Utilities::putMessageLogFile('$total_hora_semana_docenciaposgrado ' . $valores_promedio[0]['total_hora_semana_docencia_posgrado'] );
        Utilities::putMessageLogFile('$preparacion_docencia ' . $valores_promedio[0]['preparacion_docencia'] );
        Utilities::putMessageLogFile('$semanas_docencia ' . $valores_promedio[0]['semanas_docencia'] );
        Utilities::putMessageLogFile('$semanas_tutoria_vinulacion_investigacion ' . $valores_promedio[0]['semanas_tutoria_vinulacion_investigacion']);
        Utilities::putMessageLogFile('$semanas_posgrado ' . $valores_promedio[0]['semanas_posgrado']);*/
        $sumaHoras = $DistADO->sumatoriaHoras($ids);
        // Utilities::putMessageLogFile('$cabDist ' . $cabDist);

        $detDist = $DistADO->consultarDetDistributivo($ids);
        //  Utilities::putMessageLogFile('paca_id ' . $cabDist[0]['paca_id'].'-pro_id ' .$cabDist[0]['pro_id']);
        // Utilities::putMessageLogFile('total $detDist: ' . $detDist);
 //Recorre las horas para extraer sus dias y hora

        //Utilities::putMessageLogFile($detDist[0]['daho_id']);
        setlocale(LC_TIME, 'es_CO.UTF-8');
        $FechaDia = strftime("%d de %B %G", strtotime(date("d-m-Y"))); //date("j F de Y");
      //  Utilities::putMessageLogFile('paca_id ' . $cabDist[0]['paca_id'] . '-pro_id ' . $cabDist[0]['pro_id']);
        $detDistTipo = $DistADO->consultarDetDistributivoTipo($cabDist[0]['paca_id'], $cabDist[0]['pro_id']);
      //  Utilities::putMessageLogFile('detDistTipo ' . sizeof($detDistTipo));
        $detDistTotalposgrado = 0;
        $detDistTotalPrepPosgrado = 0;
        $detDistTotalVincPosgrado = 0;
        $detDistTotalgrado = 0;
        $detDistTotalPrepGrado = 0;
        $detDistTotalVincGrado = 0;

        /*if (($detDist[$fil]['tdis_id'] == 7)) {
            $detDistDocenteAutor = $DistADO->consultarDetDistributivoDocenteAutor($cabDist[0]['paca_id'], $cabDist[0]['pro_id']);
        }*/
        Utilities::putMessageLogFile('detDistTipo ' . $detDistTipo);

       /* for ($fil = 0; $fil < sizeof($detDistTipo); $fil++) {
            //Utilities::putMessageLogFile('daho_id: ' . $detDistTipo[$fil]['daho_id']);
            if ($detDistTipo[$fil]['uaca_id'] == 2) {
                //Total de horas posgrado
                $detDistTotalposgrado += $DistADO->consultarDetDistributivoTotalposgrado($detDistTipo[$fil]['daho_id']);
                //Total de preparacion docente
                $detDistTotalPrepPosgrado += $DistADO->consultarDetDistributivoPreparacionPosgrado($detDistTipo[$fil]['daho_id']);
                //Total de vinculacion e investigacion
                $detDistTotalVincPosgrado += $DistADO->consultarDetDistributivoVinculacionPosgrado($detDistTipo[$fil]['paca_id']);
                //   Utilities::putMessageLogFile('total posgrado: ' . $detDistTotalposgrado);
            } else {
                //Total de horas grado
                $detDistTotalgrado += $DistADO->consultarDetDistributivoTotalgrado($detDistTipo[$fil]['daho_id']);
                //Total de preparacion docente grado
                $detDistTotalPrepGrado += $DistADO->consultarDetDistributivoPreparacionGrado($detDistTipo[$fil]['daho_id']);
                //Total de vinculacion e investigacion grado
                $detDistTotalVincGrado += $DistADO->consultarDetDistributivoVinculacionGrado($detDistTipo[$fil]['daho_id']);
                Utilities::putMessageLogFile('total grado: ' . $detDistTotalgrado);
            }
        }*/


        $rep->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical
        $rep->createReportPdf(
                $this->render('@modules/academico/views/tpl_asignamaterias/cargahora', [
                    'cabDist' => $cabDist,
                    'detDist' => $detDist,
                    'detDistTipo' => $detDistTipo,
                    'detDistTotalposgrado' => $detDistTotalposgrado,
                    'detDistTotalgrado' => $detDistTotalgrado,
                    'detDistTotalVincPosgrado' => $detDistTotalVincPosgrado,
                    'detDistTotalVincGrado' => $detDistTotalVincGrado,
                    'detDistTotalPrepPosgrado' => $detDistTotalPrepPosgrado,
                    'detDistTotalPrepGrado' => $detDistTotalPrepGrado,
                    'detDistDocenteAutor' => $detDistDocenteAutor,
                    'FechaDia' => $FechaDia,
                    'sumaHoras'=>$sumaHoras,
                    'promedio'=>round($promedio),
                    'horas_carga_docente_bloque' => $horas_carga_docente_bloque
                ])
        );

        $rep->mpdf->Output($cabDist[0]['Nombres'] . '_' . 'BLOQUE_' . $cabDist[0]['baca_descripcion'] . '_' . $cabDist[0]['baca_anio'] . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

}