<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\EstudioAcademico;
use app\modules\academico\models\ControlCatedra;
use app\modules\admision\models\Oportunidad;
use DateTime;
use app\modules\admision\Module as admision;
use app\modules\academico\Module as academico;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;

admision::registerTranslations();

class ControlcatedraController extends \app\components\CController {

    public function actionNew() {
        $hape_id = $_GET['hape_id'];
        $modcanal = new Oportunidad();
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_control = new ControlCatedra();
        $data = Yii::$app->request->get();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getmodalidad"])) {
                $modalidad = $mod_modalidad->consultarModalidad($data["uni_id"], 1);
                $message = array("modalidad" => $modalidad);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
            if (isset($data["getcarrera"])) {
                $carrera = $modcanal->consultarCarreraModalidad($data["unidada"], $data["moda_id"]);
                $message = array("carrera" => $carrera);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_actividad = $mod_control->consultarActividadevaluacion();
        $arr_valor = $mod_control->consultarValordesarrollo();
        $arr_datahorario = $mod_control->consultarHorarioxhapeid($hape_id, true);
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $arr_modalidad = $mod_modalidad->consultarModalidad($arr_datahorario[0]["unidad"], 1);
        $arr_carrera = $modcanal->consultarCarreraModalidad($arr_datahorario[0]["unidad"], $arr_datahorario[0]["modalidad"]);
        return $this->render('new', [
                    'arr_actividad' => $arr_actividad,
                    "arr_valor" => $arr_valor,
                    "arr_datahorario" => $arr_datahorario,
                    "arr_unidad" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_unidad), "id", "name"),
                    "arr_modalidad" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_modalidad), "id", "name"),
                    "arr_carrera" => ArrayHelper::map(array_merge([["id" => "0", "name" => Yii::t("formulario", "Select")]], $arr_carrera), "id", "name"),
        ]);
    }

    public function actionIndex() {
        $mod_modalidad = new Modalidad();
        $mod_unidad = new UnidadAcademica();
        $mod_control = new ControlCatedra();
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $periodo = $mod_periodo->consultarPeriodoAcademico();
        $data = Yii::$app->request->get();
        \app\models\Utilities::putMessageLogFile('paso 1');
        if ($data['PBgetFilter']) {
            $arrSearch["profesor"] = $data['profesor'];
            $arrSearch["materia"] = $data['materia'];
            $arrSearch["f_ini"] = $data['f_ini'];
            $arrSearch["f_fin"] = $data['f_fin'];
            $arrSearch["periodo"] = $data['periodo'];
            $arrSearch["unidad"] = $data['unidad'];
            $arrSearch["modalidad"] = $data['modalidad'];
            $arrSearch["estado"] = $data['estado'];
            $arr_data = $mod_control->consultarControlCatedra($arrSearch);
            return $this->render('index-grid', [
                        'model' => $arr_data,
            ]);
        } else {
            \app\models\Utilities::putMessageLogFile('paso else filtro');
            $arrSearch["periodo"] = $periodo[0]["id"];
            \app\models\Utilities::putMessageLogFile('periodo:' . $arrSearch["periodo"]);
            $arr_data = $mod_control->consultarControlCatedra($arrSearch);
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
        }

        $unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(1);
        $modalidad = $mod_modalidad->consultarModalidad(1, 1);
        return $this->render('index', [
                    'model' => $arr_data,
                    'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $periodo), "id", "name"),
                    'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $unidad), "id", "name"),
                    'arr_modalidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Todas"]], $modalidad), "id", "name"),
                    'arr_estado' => array("0" => Yii::t("formulario", "Todas"), "1" => Yii::t("formulario", "Registrado"), "2" => Yii::t("formulario", "Sin Registrar")),
        ]);
    }

    public function actionSave() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $busqueda = 0;
        $mod_control = new ControlCatedra();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $hape_id = $data["hape_id"];
            $carrera = $data["carrera"];
            $fecha_registro = $data["fecha_registro"];
            $titulo = ucwords(strtolower($data["titulo"]));
            $tema = ucwords(strtolower($data["tema"]));
            $trabajo = ucwords(strtolower($data["trabajo"]));
            $logro = ucwords(strtolower($data["logro"]));
            $observacion = ucwords(strtolower($data["observacion"]));
            $direccionip = \app\models\Utilities::getClientRealIP(); // ip de la maquina
            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $cons_control = $mod_control->consultarControlcatedraxid($hape_id, $fecha_registro, $usu_id);
                if ($cons_control["control"] > 0) {
                    $busqueda = 1;
                }
                if ($busqueda == 0) {
                    $fecha = date(Yii::$app->params["dateTimeByDefault"]);
                    $resp_control = $mod_control->insertarControlcatedra($hape_id, $carrera, $fecha_registro, $titulo, $tema, $trabajo, $logro, $observacion, $direccionip, $usu_id, $fecha);
                    if ($resp_control) {
                        $arrActividad = $data["actividad"];
                        for ($a = 0; $a < count($arrActividad); $a++) {
                            $dataRegActividad = array(
                                'ccat_id' => $resp_control,
                                'aeva_id' => $arrActividad[$a]["actividad_id"],
                                'aeva_otro' => $data["otroactividad"],
                            );
                            $resp_actividad = $mod_control->insertarActividadcatedra($con, $dataRegActividad);
                        }
                        if ($resp_actividad) {
                            $arrValor = $data["valor"];
                            for ($b = 0; $b < count($arrValor); $b++) {
                                $dataRegValor = array(
                                    'ccat_id' => $resp_control,
                                    'vdes_id' => $arrValor[$b]["valor_id"],
                                    'vdes_otro' => $data["otrovalor"],
                                );
                                $resp_valor = $mod_control->insertarValorcatedra($con, $dataRegValor);
                            }
                            if ($resp_valor) {
                              $exito = 1;  
                            }                            
                        }
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
                } else {

                    $mensaje = 'Ya registró el control de la cátreda hoy.';
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "No se puede guardar la información. " . $mensaje),
                        "title" => Yii::t('jslang', 'Error'),
                    );
                    return Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Error"), true, $message);
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
