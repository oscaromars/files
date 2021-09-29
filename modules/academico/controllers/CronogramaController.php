<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\ExportFile;
use app\modules\academico\models\EstudioAcademico;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use app\modules\academico\models\Modalidad;
use app\modules\academico\models\UnidadAcademica;
use app\modules\academico\models\ModuloEstudio;
use app\modules\academico\models\PeriodoAcademicoMetIngreso;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\models\Cronograma;
use app\modules\academico\models\Especies;
use app\modules\academico\models\Reglamento;
use app\models\Persona;
use app\models\Usuario;
use yii\base\Security;
use yii\base\Exception;
use app\modules\academico\Module as academico;

academico::registerTranslations();

class CronogramaController extends \app\components\CController {

    public function actionNew() {
        $mod_periodo = new PeriodoAcademicoMetIngreso();
        $mod_unidad = new UnidadAcademica();
        $periodo = $mod_periodo->consultarPeriodoAcademico();
        $arr_unidad = $mod_unidad->consultarUnidadAcademicasEmpresa(0);
        // pagina para subir una imagen de cronograma
        return $this->render('new', [
            'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Seleccionar"]], $periodo), "id", "name"),
            'arr_unidad' => ArrayHelper::map(array_merge([["id" => "0", "name" => "Seleccionar"]], $arr_unidad), "id", "name"),
        ]);
    }

    public function actionCronograma() {
        $per_id = @Yii::$app->session->get("PB_perid");
        // pagina para visualizar la imagen de cronograma
        // obtener periodo actual
        $mod_periodo = new PeriodoAcademico();
        $mod_especie = new Especies();
        $mod_cronograma = new Cronograma();
        $periodo_activo = $mod_periodo->getPeriodoAcademicoActual();
        // obtener la unidad academica del estudiante autenticado
        $unidad_academica = $mod_especie->consultaDatosEstudiante($per_id);
        //obtener la imagen guardad del cronograma segun unidad academica y periodo actual
        $cronograma = $mod_cronograma->consultaCronograma($unidad_academica["uaca_id"], $periodo_activo[0]["id"]);
        return $this->render('cronograma', [
            'arr_cronograma' => $cronograma,
        ]);
    }

    public function actionCargarcronograma() {
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $modcronograma = new Cronograma();
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if ($data["upload_file"]) {
                if (empty($_FILES)) {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
                //Recibe Parametros
                $files = $_FILES[key($_FILES)];
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                $dirFileEnd = Yii::$app->params["documentFolder"] . "cronogramas/" . $data["name_file"] . "." . $typeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirFileEnd);
                if ($status) {
                    return true;
                } else {
                    echo json_encode(['error' => Yii::t("notificaciones", "Error to process File {file}. Try again.", ['{file}' => basename($files['name'])])]);
                    return;
                }
            }
            $arrIm = explode(".", basename($data["documento"]));
            $typeFile = strtolower($arrIm[count($arrIm) - 1]);
            $imagen = $arrIm[0] . "." . $typeFile;
            $uaca_id = $data["uaca_id"];
            $paca_id = $data["paca_id"];
            $cro_descripcion = ucwords(mb_strtolower($data["descripcion"]));

            $con = \Yii::$app->db_academico;
            $transaction = $con->beginTransaction();
            try {
                $modcronograma = new Cronograma();
                $cronograma = $modcronograma->consultaCronograma($uaca_id, $paca_id);
                //guardar la data Aqui
                if (empty($cronograma["cro_archivo"])) {
                $respcronograma = $modcronograma->insertarCronograma($uaca_id, $paca_id, $imagen, $cro_descripcion, $usu_id);
                if ($respcronograma) {
                    $exito = 1;
                }
                if ($exito == 1) {
                    $transaction->commit();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Cronograma ha sido cargado."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                } else {
                    $transaction->rollback();
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Error: Cronograma No ha sido cargado."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
                }
              } else {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Se actualizó la imagen del cronograma."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return \app\models\Utilities::ajaxResponse('OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            } catch (Exception $ex) {
                $transaction->rollback();
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Error al grabar."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                return \app\models\Utilities::ajaxResponse('NO_OK', 'alert', Yii::t("jslang", "Sucess"), false, $message);
            }
            return;
        }
    }
    public function actionReglamento() {
        //$data = Yii::$app->request->get();
        $usu_id = @Yii::$app->session->get("PB_iduser");
        $modreglamento = new Reglamento();
        $reglamento = $modreglamento->consultaReglamento();
        return $this->render('reglamento', [
            'model' => $reglamento,
        ]);
    }

    public function actionDownload($route, $type) {
        //\app\models\Utilities::putMessageLogFile('route 1: ' . $route);
        if (Yii::$app->session->get('PB_isuser')) {
            $route = str_replace("../", "", $route);
            //\app\models\Utilities::putMessageLogFile('route 2: ' . $route);
            //if (preg_match("/^" . reglamentos . "\//", $route)) {
                $url_image = Yii::$app->basePath . "/uploads/reglamentos/" . $route;
                //\app\models\Utilities::putMessageLogFile('url_image: ' . $url_image);
                $arrIm = explode(".", $url_image);
                $typeImage = $arrIm[count($arrIm) - 1];
                if (file_exists($url_image)) {
                    if (strtolower($typeImage) == "pdf") {
                        header('Pragma: public');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                        header('Cache-Control: private', false);
                        header("Content-type: application/pdf");
                        if ($type == "view") {
                            header('Content-Disposition: inline; filename="reglamento_' . time() . '.pdf";');
                        } else {
                            header('Content-Disposition: attachment; filename="reglamento__' . time() . '.pdf";');
                        }
                        header('Content-Transfer-Encoding: binary');
                        header('Content-Length: ' . filesize($url_image));
                        readfile($url_image);
                    }
                }
            //}
        }
        exit();
    }

}