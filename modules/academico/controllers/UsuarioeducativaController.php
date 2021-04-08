<?php

namespace app\modules\academico\controllers;

use Yii;
use app\models\Persona;
use app\models\Usuario;
use yii\helpers\ArrayHelper;
use app\models\Utilities;
use yii\base\Exception;
use app\modules\academico\models\UsuarioEducativa;

class UsuarioeducativaController extends \app\components\CController {

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
                \app\models\Utilities::putMessageLogFile('Files ...: ' . $data["archivo"]);
                $carga_archivo = $mod_educativa->CargarArchivoeducativa($data["archivo"]);
                if ($carga_archivo['status']) {
                    \app\models\Utilities::putMessageLogFile('no estudiante controller...: ' . $arroout['noalumno']);
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
}  