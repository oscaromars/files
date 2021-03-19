<?php

namespace app\modules\academico\controllers;

use Yii;
use app\modules\academico\models\DistributivoCabecera;
use app\modules\academico\models\DistributivoAcademico;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;
use app\models\ExportFile;
use app\modules\academico\models\Profesor;
use Exception;
use app\models\UsuaGrolEper;
use app\models\Persona;

academico::registerTranslations();
admision::registerTranslations();

class HomologacionController extends \app\components\CController
{
    public $pdf_cla_acceso = "";
    private function estados()
    {
        return [
            '0' => Yii::t("formulario", "Todos"),
            '1' => Yii::t("formulario", "Por aprobar"),
            '2' => Yii::t("formulario", "Aprobado"),
            '3' => Yii::t("formulario", "No aprobado"),
        ];
    }

    private function estadoRevision()
    {
        return [
            '0' => Yii::t("formulario", "Seleccionar"),
            '2' => Yii::t("formulario", "APPROVED"),
            '3' => Yii::t("formulario", "Not approved"),
        ];
    }

    public function actionIndex()
    {
        $per_id = @Yii::$app->session->get("PB_perid");
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $rol    = UsuaGrolEper::find()->select("grol_id")->where(["eper_id" => $per_id,"usu_id" => $usu_id])->asArray()->all();
        $cedula = Persona::find()->select("per_cedula")->where(["per_id" => $per_id])->asArray()->all();


        return $this->render('index', [
            'per_id' => $per_id,
            'usu_id' => $usu_id,
            'rol'    => $rol[0]['grol_id'],
            'cedula' => $cedula[0]['per_cedula'],
        ]);
    }

    public function actionExportexcel()
    {
        ini_set('memory_limit', '256M');
        $content_type = Utilities::mimeContentType("xls");
        $nombarch = "Report-" . date("YmdHis") . ".xls";
        header("Content-Type: $content_type");
        header("Content-Disposition: attachment;filename=" . $nombarch);
        header('Cache-Control: max-age=0');
        $colPosition = array("C", "D", "E", "F", "G", "H", "I");
        $arrHeader = array(
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "DNI 1"),
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "Status"),
        );
        $distributivocab_model = new DistributivoCabecera();
        $data = Yii::$app->request->get();

        $arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0) ? $data['periodo'] : NULL;
        $arrSearch["estado"] = ($data['estado'] > 0) ? $data['estado'] : NULL;

        $arrData = $distributivocab_model->getListadoDistributivoCab($arrSearch["search"], $arrSearch["periodo"], $arrSearch["estado"], true);

        foreach ($arrData as $key => $value) {
            unset($arrData[$key]["Id"]);
        }
        $nameReport = academico::t("distributivoacademico", "List of Distributive Teachers");
        Utilities::generarReporteXLS($nombarch, $nameReport, $arrHeader, $arrData, $colPosition);
        exit;
    }

    public function actionExportpdf()
    {
        $report = new ExportFile();
        $this->view->title = academico::t("distributivoacademico", "List of Distributive Teachers"); // Titulo del reporte
        $arrHeader = array(
            Yii::t("formulario", "Period"),
            Yii::t("formulario", "DNI 1"),
            academico::t("Academico", "Teacher"),
            Yii::t("formulario", "Status"),
        );
        $distributivocab_model = new DistributivoCabecera();
        $data = Yii::$app->request->get();
        $arrSearch["search"] = ($data['search'] != "") ? $data['search'] : NULL;
        $arrSearch["periodo"] = ($data['periodo'] > 0) ? $data['periodo'] : NULL;
        $arrSearch["estado"] = ($data['estado'] > 0) ? $data['estado'] : NULL;

        $arrData = $distributivocab_model->getListadoDistributivoCab($arrSearch["search"], $arrSearch["periodo"], $arrSearch["estado"], true);
        $report->orientation = "P"; // tipo de orientacion L => Horizontal, P => Vertical                                
        $report->createReportPdf(
            $this->render('exportpdf', [
                'arr_head' => $arrHeader,
                'arr_body' => $arrData,
            ])
        );
        $report->mpdf->Output('Reporte_' . date("Ymdhis") . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }

    public function actionDeletecab()
    {
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

    public function actionReview($id)
    {
        $distributivo_model = new DistributivoAcademico();
        $distributivo_cab = new DistributivoCabecera();
        $resCab = $distributivo_cab->obtenerDatosCabecera($id);
        $arr_distributivo = $distributivo_model->getListarDistribProfesor($resCab["paca_id"], $resCab["pro_id"]);
        return $this->render('review', [
            'arr_cabecera' => $resCab,
            'arr_detalle' => $arr_distributivo,
            'arr_estado' => $this->estadoRevision(),
        ]);
    }

    public function actionSavereview()
    {
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

    public function actionValidadistributivo()
    {
        $distributivo_cab = new DistributivoCabecera();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $pro_id = $data["pro_id"];
            $paca_id = $data["paca_id"];
            $tran_id = $data["transaccion"];
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
                        $message = array(
                            "wtmessage" => Yii::t('notificaciones', 'OK'),
                            "title" => Yii::t('jslang', 'Success'),
                        );
                        return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
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

    public function actionGenerarmateriacarga($ids)
    {
        //try {

        $ids = isset($_GET['ids']) ? base64_decode($_GET['ids']) : NULL;
        $rep = new ExportFile();
        //$this->layout = false;
        $this->layout = '@modules/academico/views/tpl_asignamaterias/main';
        //$this->view->title = "Invoices";

        $DistADO = new DistributivoCabecera();
        $cabDist = $DistADO->consultarCabDistributivo($ids);

        $detDist = $DistADO->consultarDetDistributivo($cabDist[0]['paca_id'], $cabDist[0]['pro_id']);
        //Recorre las horas para extraer sus dias y hora
        for ($fil = 0; $fil < sizeof($detDist); $fil++) {
            //Si tipo Distributivo =1 Tiene datos en la tabla distributivo horas
            if ($detDist[$fil]['tdis_id'] == 1) {
                $horaDist = $DistADO->consultarDistHoras($detDist[$fil]['daho_id']);
                $detDist[$fil]['HORAS'] = $horaDist[0]['HORAS'];
                //Totales del reporte
                //$detDist[$fil]['DIAS']="N/A";
                //$detDist[$fil]['HORAS']="N/A";
            }
        }
        //Utilities::putMessageLogFile($detDist[0]['daho_id']);
        setlocale(LC_TIME, 'es_CO.UTF-8');
        $FechaDia = strftime("%d de %B %G", strtotime(date("d-m-Y"))); //date("j F de Y");   

        $detDistTipo = $DistADO->consultarDetDistributivoTipo($cabDist[0]['paca_id'], $cabDist[0]['pro_id']);
        $detDistTotalposgrado = 0;
        $detDistTotalPrepPosgrado = 0;
        $detDistTotalVincPosgrado = 0;
        $detDistTotalgrado = 0;
        $detDistTotalPrepGrado = 0;
        $detDistTotalVincGrado = 0;

        for ($fil = 0; $fil < sizeof($detDistTipo); $fil++) {

            if ($detDistTipo[$fil]['uaca_id'] == 2) {
                //Total de horas posgrado
                $detDistTotalposgrado  +=  $DistADO->consultarDetDistributivoTotalposgrado($detDistTipo[$fil]['daho_id']);
                //Total de preparacion docente
                $detDistTotalPrepPosgrado +=  $DistADO->consultarDetDistributivoPreparacionPosgrado($detDistTipo[$fil]['daho_id']);
                //Total de vinculacion e investigacion
                $detDistTotalVincPosgrado +=  $DistADO->consultarDetDistributivoVinculacionPosgrado($detDistTipo[$fil]['daho_id']);
                Utilities::putMessageLogFile('total posgrado: ' . $detDistTotalposgrado);
            } else {
                //Total de horas grado
                $detDistTotalgrado +=  $DistADO->consultarDetDistributivoTotalgrado($detDistTipo[$fil]['daho_id']);
                //Total de preparacion docente grado
                $detDistTotalPrepGrado +=  $DistADO->consultarDetDistributivoPreparacionGrado($detDistTipo[$fil]['daho_id']);
                //Total de vinculacion e investigacion grado
                $detDistTotalVincGrado +=  $DistADO->consultarDetDistributivoVinculacionGrado($detDistTipo[$fil]['daho_id']);
                Utilities::putMessageLogFile('total grado: ' . $detDistTotalgrado);
            }
        }


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
                'FechaDia' => $FechaDia,
            ])
        );

        $rep->mpdf->Output($cabDist[0]['Nombres'] . '_' . 'BLOQUE_' . $cabDist[0]['baca_descripcion'] . '_' . $cabDist[0]['baca_anio'] . ".pdf", ExportFile::OUTPUT_TO_DOWNLOAD);
    }
}
