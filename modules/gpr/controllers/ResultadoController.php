<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\models\Utilities;
use app\models\Empresa;
use app\modules\gpr\models\ComportamientoIndicador;
use app\modules\gpr\models\FrecuenciaIndicador;
use app\modules\gpr\models\Indicador;
use app\modules\gpr\models\MetaAnexo;
use app\modules\gpr\models\MetaIndicador;
use app\modules\gpr\models\MetaSeguimiento;
use app\modules\gpr\models\Nivel;
use app\modules\gpr\models\PlanificacionPoa;
use app\modules\gpr\models\ResponsableUnidad;
use app\modules\gpr\models\TipoMeta;
use app\modules\gpr\models\Umbral;
use app\modules\gpr\models\UnidadMedida;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;

gpr::registerTranslations();

class ResultadoController extends \app\components\CController {
    public $limitSizeFile = "8000000"; // 8 MB
    public $extensionAvailable = "pdf"; // pdf, jpeg, png

    public function actionIndex($id) {
        // se debe crear los registros vacios solo con el periodo
        $indicador = Indicador::findOne($id);
        $data = Yii::$app->request->get();
        $tipoMeta = TipoMeta::findOne($indicador->tmet_id);
        $comportamiento = ComportamientoIndicador::findOne($indicador->cind_id);
        $unidadMedida = UnidadMedida::findOne($indicador->umed_id);
        $modelUmbrales = Umbral::find(['umb_estado' => '1', 'umb_estado_logico' => '1'])->asArray()->all();
        $ugpr_id = $indicador->ugpr_id;
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $niv_id = Nivel::getLevel($user_id, $idEmpresa, $ugpr_id);
        /*if (isset($data["PBgetFilter"])) {
            $arr_data = MetaSeguimiento::getAllSeguimientoGrid($id);
            return $this->renderPartial('index', [
                'model' => $arr_data,
                'ind_fraccional' => $indicador->ind_fraccional,
                'indicador' => $indicador,
                'tipometa' => $tipoMeta->tmet_nombre,
                'comportamiento' => $comportamiento->cind_nombre,
                'modelUmbrales' => $modelUmbrales,
                'limitsizefile' => $this->limitSizeFile,
                'extensionAvailable' => $this->extensionAvailable,
                'niv_id' => $niv_id,
            ]);
        }*/
        $_SESSION['JSLANG']['Edit Goal'] = gpr::t('meta', 'Edit Goal');
        $_SESSION['JSLANG']['Period'] = gpr::t('meta', 'Period');
        $_SESSION['JSLANG']['Period Goal'] = gpr::t('meta', 'Period Goal');
        $_SESSION['JSLANG']['Numerator'] = gpr::t('meta', 'Numerator');
        $_SESSION['JSLANG']['Denominator'] = gpr::t('meta', 'Denominator');
        $_SESSION['JSLANG']['Result'] = gpr::t('meta', 'Result');
        $_SESSION['JSLANG']['Advance Period'] = gpr::t('meta', 'Advance Period');
        $_SESSION['JSLANG']['Attached File Name'] = gpr::t('meta', 'Attached File Name');
        $_SESSION['JSLANG']['Attached File Name Description'] = gpr::t('meta', 'Attached File Name Description');
        $_SESSION['JSLANG']['Attach File'] = gpr::t('meta', 'Attach File');
        $_SESSION['JSLANG']['LoadFile'] = Yii::t('accion', 'LoadFile');
        $_SESSION['JSLANG']['Please attach a File Name.'] = gpr::t('meta', 'Please attach a File Name.');
        $_SESSION['JSLANG']['Comments'] = gpr::t('meta', 'Comments');

        $model_meta = MetaIndicador::findOne(['ind_id' => $id, 'mind_estado' => '1', 'mind_estado_logico' => '1']);
        $model = MetaSeguimiento::findOne(['mind_id' => $model_meta->mind_id, 'mseg_estado' => '1', 'mseg_estado_logico' => '1']);
        if(!$model){
            if(!MetaSeguimiento::inicializarMetaSeguimiento($id))
                Yii::$app->session->setFlash('error',"<h4>".Yii::t('notificaciones', 'Error')."</h4>".gpr::t("meta","Results cannot be showed. Report to Administrator."));
        }
        $arr_data = MetaSeguimiento::getAllSeguimientoGrid($id);
        if($arr_data->getTotalCount() == 0)
            Yii::$app->session->setFlash('error',"<h4>".Yii::t('notificaciones', 'Error')."</h4>".gpr::t("meta","Results cannot be showed. First enter Goals and Save/Close Proyection."));
        $isAdmin = ResponsableUnidad::userIsAdmin($user_id, $idEmpresa);
        return $this->render('index', [
            'model' => $arr_data,
            'ind_fraccional' => $indicador->ind_fraccional,
            'indicador' => $indicador,
            'tipometa' => $tipoMeta->tmet_nombre,
            'comportamiento' => $comportamiento->cind_nombre,
            'unidadmedida' => $unidadMedida->umed_nombre,
            'modelUmbrales' => $modelUmbrales,
            'limitsizefile' => $this->limitSizeFile,
            'extensionAvailable' => $this->extensionAvailable,
            'niv_id' => $niv_id,
            'isAdmin' => $isAdmin,
        ]);
    }
    public function actionSave() { 
        // crea la meta real o seguimienti y cierra el periodo para que no pueda ser editado
        if (Yii::$app->request->isAjax) {
            $per_id = Yii::$app->session->get("PB_perid");
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $data = Yii::$app->request->post();
            $dirFile = Yii::$app->params["documentFolder"] . "gpr/anexos/";
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            $msg = "";
            try{
                $mseg_id = $data['id'];
                $meta = $data['meta'];
                $resultado = $data['resultado'];
                $avance = $data['avance'];
                $numerador = (isset($data['numerador']) && $data['numerador'] != "")?$data['numerador']:NULL;
                $denominador = (isset($data['denominador']) && $data['denominador'] != "")?$data['denominador']:NULL;
                $comentario = (isset($data['comentario']) && $data['comentario'] != "")?$data['comentario']:NULL;
                $descripcion = $data['descripcion'];
                $anexo = $data['anexo'];
                $files = $_FILES[key($_FILES)];
                $modelSeg = MetaSeguimiento::findOne($mseg_id);
                $modelMet = MetaIndicador::findOne($modelSeg->mind_id);
                $indicador = Indicador::findOne($modelMet->ind_id);
                $planCierre = PlanificacionPoa::getCierrePoaPedi($indicador->oope_id);
                if($planCierre['pedi_cierre'] == '1' || $planCierre['poa_cierre'] == '1'){
                    $error = true;
                    throw new Exception(gpr::t('planificacionpoa', 'Transaction is invalid. Planning Pedi/Poa is closed.'));
                }
                if (!isset($files) || count($files) == 0) {
                    $error = true;
                    throw new Exception(Yii::t("jslang", "Error to process File {file}. Try again.", ['file' => basename($files['name'])]));
                }
                //Recibe Paramentros
                $arrIm = explode(".", basename($files['name']));
                $typeFile = strtolower($arrIm[count($arrIm) - 1]);
                if(!in_array($typeFile, explode(',',$this->extensionAvailable))){
                    $error = true;
                    throw new Exception(Yii::t("jslang", "{file} extension is invalid. Only {extensions} are allowed.", ['file' => basename($files['name']), 'extensions' => $this->extensionAvailable]));
                }
                if($files['size'] > $this->limitSizeFile){
                    $error = true;
                    throw new Exception(Yii::t("jslang", "{file} is too large, maximum file size is {sizeLimit}.", ['file' => basename($files['name']), 'sizeLimit' => round(($this->limitSizeFile) / 1024 / 1024, 2) . " MB" ]));
                }
                $name_file = str_replace(" ", "_", $arrIm[count($arrIm) - 2]);
                $relativeFile = $mseg_id . "/" . $name_file . "_per_" . $per_id . "_" . date('YmdHi') . "." . $typeFile;
                $dirCurrent = $dirFile . $relativeFile;
                $status = Utilities::moveUploadFile($files['tmp_name'], $dirCurrent);
                if ($status) {
                    $modelSeg->mseg_numerador = $numerador;
                    $modelSeg->mseg_resultado = $resultado;
                    $modelSeg->mseg_avance = $avance;
                    $modelSeg->mseg_comentario = $comentario;
                    $modelSeg->mseg_usuario_modifica = $user_id;
                    $modelSeg->mseg_fecha_modificacion = $fecha_modificacion;
                    $modelSeg->mseg_fecha_fin = $fecha_modificacion;
                    $modelSeg->mseg_periodo_cerrado = '1';
                    
                    $modelAnexo = new MetaAnexo();
                    if($modelSeg->save()){
                        if(MetaAnexo::disableOldDocuments($mseg_id) == false)
                            throw new Exception('Error Imposible Desactivar documentos antiguos.');
                        $modelAnexo->mseg_id = $mseg_id;
                        $modelAnexo->mane_nombre = $name_file;
                        $modelAnexo->mane_ruta = $dirCurrent;
                        $modelAnexo->mane_usuario_ingreso = $user_id;
                        $modelAnexo->mane_estado = '1';
                        $modelAnexo->mane_estado_logico = '1';
                        $modelAnexo->mane_descripcion = $descripcion;
                        if($modelAnexo->save()){
                            $transaction->commit();
                            $message = array(
                                "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                                "title" => Yii::t('jslang', 'Success'),
                            );
                            return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                        }
                    }
                    throw new Exception('Error Registro no actualizado.');
                }else{
                    throw new Exception(Yii::t("notificaciones", "Error to process File {file}. Try again.", ['file' => basename($files['name'])]));
                }
            }catch(Exception $e){
                $transaction->rollback();
                $msg = ($error)?($e->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.') . " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
    public function actionOpenresultado(){
        // permite abrir un resultado para que pueda ser editado nuevamente
        if (Yii::$app->request->isAjax) {
            $per_id = Yii::$app->session->get("PB_perid");
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $emp_id = Yii::$app->session->get("PB_idempresa", FALSE);
            $data = Yii::$app->request->post();
            $id = $data['id'];
            $error = false;
            try{
                if(!ResponsableUnidad::userIsAdmin($user_id, $emp_id)){
                    $error = true;
                    throw new Exception(gpr::t("responsablesubunidad", "User have no privileges N1 to do this transaccion."));
                }
                $modelSeg = MetaSeguimiento::findOne($id);
                $modelSeg->mseg_periodo_cerrado = '0';
                $modelSeg->mseg_usuario_modifica = $user_id;
                $modelSeg->mseg_fecha_modificacion = $fecha_modificacion;
                $modelSeg->mseg_fecha_fin = $fecha_modificacion;
                $modelMet = MetaIndicador::findOne($modelSeg->mind_id);
                $indicador = Indicador::findOne($modelMet->ind_id);
                $planCierre = PlanificacionPoa::getCierrePoaPedi($indicador->oope_id);
                if($planCierre['pedi_cierre'] == '1' || $planCierre['poa_cierre'] == '1'){
                    $error = true;
                    throw new Exception(gpr::t('planificacionpoa', 'Transaction is invalid. Planning Pedi/Poa is closed.'));
                }
                if($modelSeg->save()){
                    $message = array(
                        "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                        "title" => Yii::t('jslang', 'Success'),
                    );
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                }
                
            }catch(Exception $e){
                $msg = ($error)?($e->getMessage()):"";
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'). " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
    public function actionDownloadanexo($id){
        if (Yii::$app->session->get('PB_isuser')) {
            $modelAnexo = MetaAnexo::findOne(['mane_estado' => '1', 'mane_estado_logico' => '1', 'mseg_id' => $id]);
            $dirFile = Yii::$app->params["documentFolder"] . "gpr/anexos/";
            if($modelAnexo){
                $route = str_replace("../", "", $modelAnexo->mane_ruta);
                $url_file = Yii::$app->basePath . $route;
                if (file_exists($url_file)) {
                    header('Pragma: public');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Cache-Control: private', false);
                    header("Content-type: application/pdf");
                    header('Content-Disposition: attachment; filename="' . basename($route) .'";');
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . filesize($url_file));
                    readfile($url_file);
                }
            }
        }
        exit();
    }
}