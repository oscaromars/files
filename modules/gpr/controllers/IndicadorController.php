<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\models\Utilities;
use app\models\Empresa;
use app\modules\gpr\models\ComportamientoIndicador;
use app\modules\gpr\models\FrecuenciaIndicador;
use app\modules\gpr\models\Indicador;
use app\modules\gpr\models\JerarquiaIndicador;
use app\modules\gpr\models\MetaAnexo;
use app\modules\gpr\models\MetaIndicador;
use app\modules\gpr\models\MetaSeguimiento;
use app\modules\gpr\models\ObjetivoEspecifico;
use app\modules\gpr\models\ObjetivoOperativo;
use app\modules\gpr\models\PatronIndicador;
use app\modules\gpr\models\PlanificacionPoa;
use app\modules\gpr\models\TipoAgrupacion;
use app\modules\gpr\models\TipoConfiguracion;
use app\modules\gpr\models\TipoMeta;
use app\modules\gpr\models\UnidadGpr;
use app\modules\gpr\models\UnidadMedida;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class IndicadorController extends \app\components\CController {

    public function actionIndex() {
        $model = new Indicador();
        $data = Yii::$app->request->get();
        if (isset($data["PBgetFilter"])) {
            return $this->renderPartial('index-grid', [
                "model" => $model->getAllIndicadorGrid($data["search"], $data["objetivo"], $data["plan"], true)
            ]);
        }
        $arr_objesp = ObjetivoOperativo::getArrayObjOperativo();
        $arr_objesp = ['0' => gpr::t('objetivooperativo', '-- All Operative Objective --')] + ArrayHelper::map($arr_objesp, "id", "name");
        $arr_plan = PlanificacionPoa::findAll(['ppoa_estado' => '1', 'ppoa_estado_logico' => '1']);
        $arr_plan = ['0' => gpr::t('planificacionpoa', '-- All Poa Planning --')] + ArrayHelper::map($arr_plan, "ppoa_id", "ppoa_nombre");
        return $this->render('index', [
            'model' => $model->getAllIndicadorGrid(NULL, NULL, NULL, true),
            'arr_objesp' => $arr_objesp,
            'arr_plan' => $arr_plan,
        ]);
    }

    public function actionNew() {
        $_SESSION['JSLANG']['Please select an Unit of Measure.'] = gpr::t('indicador', 'Please select an Unit of Measure.');
        $_SESSION['JSLANG']['Please select a Hierarchy.'] = gpr::t('indicador', 'Please select a Hierarchy.');
        $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
        $_SESSION['JSLANG']['Please select an Indicator Pattern.'] = gpr::t('indicador', 'Please select an Indicator Pattern.');
        $_SESSION['JSLANG']['Please select a Configuration Type.'] = gpr::t('tipoconfiguracion', 'Please select a Configuration Type.');
        $_SESSION['JSLANG']['Please select an Operative Objective.'] = gpr::t('objetivooperativo', 'Please select an Operative Objective.');
        $_SESSION['JSLANG']['Please select an Indicator Behavior.'] = gpr::t('indicador', 'Please select an Indicator Behavior.');
        $_SESSION['JSLANG']['Please select a Goal Type.'] = gpr::t('meta', 'Please select a Goal Type.');
        $_SESSION['JSLANG']['Please select an Indicator Frecuency.'] = gpr::t('indicador', 'Please select an Indicator Frecuency.');
        $_SESSION['JSLANG']['Please if Unit of Measure is percentage you have to select an Indicator Fractional as Yes.'] = gpr::t('indicador', 'Please if Unit of Measure is percentage you have to select an Indicator Fractional as Yes.');
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $arr_unidad_medida = UnidadMedida::findAll(['umed_estado' => '1', 'umed_estado_logico' => '1']);
        $arr_unidad_medida = ['0' => gpr::t('indicador', "-- Select an Unit of Measure --")] + ArrayHelper::map($arr_unidad_medida, "umed_id", "umed_nombre");
        $arr_jerarquia = JerarquiaIndicador::findAll(['jind_estado' => '1', 'jind_estado_logico' => '1']);
        $arr_jerarquia = ['0' => gpr::t('indicador', "-- Select a Hierarchy --")] + ArrayHelper::map($arr_jerarquia, "jind_id", "jind_nombre");
        $arr_unidad = UnidadGpr::getArrayUnidad();
        $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
        $arr_tconf = TipoConfiguracion::findAll(['tcon_estado' => '1', 'tcon_estado_logico' => '1']);
        $arr_tconf = ['0' => gpr::t('tipoconfiguracion', '-- Select a Configuration Type --')] + ArrayHelper::map($arr_tconf, "tcon_id", "tcon_nombre");
        $arr_patron = PatronIndicador::findAll(['pind_estado' => '1', 'pind_estado_logico' => '1']);
        $arr_patron = ['0' => gpr::t('indicador', '-- Select an Indicator Pattern --')] + ArrayHelper::map($arr_patron, "pind_id", "pind_nombre");
        $arr_obj_operativo = ObjetivoOperativo::getArrayObjOperativo();
        $arr_obj_operativo = ['0' => gpr::t('objetivooperativo', '-- Select an Operative Objective --')] + ArrayHelper::map($arr_obj_operativo, "id", "name");
        $arr_comportamiento = ComportamientoIndicador::findAll(['cind_estado' => '1', 'cind_estado_logico' => '1']);
        $arr_comportamiento = /*['0' => gpr::t('indicador', '-- Select an Indicator Behavior --')] + */ ArrayHelper::map($arr_comportamiento, "cind_id", "cind_nombre");
        $arr_tmeta = TipoMeta::findAll(['tmet_estado' => '1', 'tmet_estado_logico' => '1']);
        $arr_tmeta = /*['0' => gpr::t('meta', '-- Select a Goal Type --')] +*/ ArrayHelper::map($arr_tmeta, "tmet_id", "tmet_nombre");
        $arr_frecuencia = FrecuenciaIndicador::findAll(['find_estado' => '1', 'find_estado_logico' => '1']);
        $arr_frecuencia = ['0' => gpr::t('indicador', '-- Select an Indicator Frecuency --')] + ArrayHelper::map($arr_frecuencia, "find_id", "find_nombre");
        $arr_fraccional =  ['0' => gpr::t('indicador', 'No'), '1' => gpr::t('indicador', 'Yes')];
        $arr_agrupacion =  ['0' => gpr::t('indicador', 'No'), '1' => gpr::t('indicador', 'Yes')];
        $arr_tagrupacion = TipoAgrupacion::findAll(['tagr_estado' => '1', 'tagr_estado_logico' => '1']);
        $arr_tagrupacion = ArrayHelper::map($arr_tagrupacion, "tagr_id", "tagr_nombre");
        return $this->render('new', [
            'arr_unidad_medida' => $arr_unidad_medida,
            'arr_jerarquia' => $arr_jerarquia,
            'arr_unidad' => $arr_unidad,
            'arr_tconf' => $arr_tconf,
            'arr_patron' => $arr_patron,
            'arr_obj_operativo' => $arr_obj_operativo,
            'arr_comportamiento' => $arr_comportamiento,
            'arr_tmeta' => $arr_tmeta,
            'arr_frecuencia' => $arr_frecuencia,
            'arr_fraccional' => $arr_fraccional,
            'arr_agrupacion' => $arr_agrupacion,
            'arr_tagrupacion' => $arr_tagrupacion,
        ]);
    }

    public function actionView() {
        $data = Yii::$app->request->get();
        
        if (isset($data['id'])) {
            $_SESSION['JSLANG']['Please select an Unit of Measure.'] = gpr::t('indicador', 'Please select an Unit of Measure.');
            $_SESSION['JSLANG']['Please select a Hierarchy.'] = gpr::t('indicador', 'Please select a Hierarchy.');
            $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
            $_SESSION['JSLANG']['Please select an Indicator Pattern.'] = gpr::t('indicador', 'Please select an Indicator Pattern.');
            $_SESSION['JSLANG']['Please select a Configuration Type.'] = gpr::t('tipoconfiguracion', 'Please select a Configuration Type.');
            $_SESSION['JSLANG']['Please select an Operative Objective.'] = gpr::t('objetivooperativo', 'Please select an Operative Objective.');
            $_SESSION['JSLANG']['Please select an Indicator Behavior.'] = gpr::t('indicador', 'Please select an Indicator Behavior.');
            $_SESSION['JSLANG']['Please select a Goal Type.'] = gpr::t('meta', 'Please select a Goal Type.');
            $_SESSION['JSLANG']['Please select an Indicator Frecuency.'] = gpr::t('indicador', 'Please select an Indicator Frecuency.');
            $_SESSION['JSLANG']['Please if Unit of Measure is percentage you have to select an Indicator Fractional as Yes.'] = gpr::t('indicador', 'Please if Unit of Measure is percentage you have to select an Indicator Fractional as Yes.');
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $arr_unidad_medida = UnidadMedida::findAll(['umed_estado' => '1', 'umed_estado_logico' => '1']);
            $arr_unidad_medida = ['0' => gpr::t('indicador', "-- Select an Unit of Measure --")] + ArrayHelper::map($arr_unidad_medida, "umed_id", "umed_nombre");
            $arr_jerarquia = JerarquiaIndicador::findAll(['jind_estado' => '1', 'jind_estado_logico' => '1']);
            $arr_jerarquia = ['0' => gpr::t('indicador', "-- Select a Hierarchy --")] + ArrayHelper::map($arr_jerarquia, "jind_id", "jind_nombre");
            $arr_unidad = UnidadGpr::getArrayUnidad();
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
            $arr_tconf = TipoConfiguracion::findAll(['tcon_estado' => '1', 'tcon_estado_logico' => '1']);
            $arr_tconf = ['0' => gpr::t('tipoconfiguracion', '-- Select a Configuration Type --')] + ArrayHelper::map($arr_tconf, "tcon_id", "tcon_nombre");
            $arr_patron = PatronIndicador::findAll(['pind_estado' => '1', 'pind_estado_logico' => '1']);
            $arr_patron = ['0' => gpr::t('indicador', '-- Select an Indicator Pattern --')] + ArrayHelper::map($arr_patron, "pind_id", "pind_nombre");
            $arr_obj_operativo = ObjetivoOperativo::getArrayObjOperativo();
            $arr_obj_operativo = ['0' => gpr::t('objetivooperativo', '-- Select an Operative Objective --')] + ArrayHelper::map($arr_obj_operativo, "id", "name");
            $arr_comportamiento = ComportamientoIndicador::findAll(['cind_estado' => '1', 'cind_estado_logico' => '1']);
            $arr_comportamiento = /*['0' => gpr::t('indicador', '-- Select an Indicator Behavior --')] + */ ArrayHelper::map($arr_comportamiento, "cind_id", "cind_nombre");
            $arr_tmeta = TipoMeta::findAll(['tmet_estado' => '1', 'tmet_estado_logico' => '1']);
            $arr_tmeta = /*['0' => gpr::t('meta', '-- Select a Goal Type --')] +*/ ArrayHelper::map($arr_tmeta, "tmet_id", "tmet_nombre");
            $arr_frecuencia = FrecuenciaIndicador::findAll(['find_estado' => '1', 'find_estado_logico' => '1']);
            $arr_frecuencia = ['0' => gpr::t('indicador', '-- Select an Indicator Frecuency --')] + ArrayHelper::map($arr_frecuencia, "find_id", "find_nombre");
            $arr_fraccional =  ['0' => gpr::t('indicador', 'No'), '1' => gpr::t('indicador', 'Yes')];
            $arr_agrupacion =  ['0' => gpr::t('indicador', 'No'), '1' => gpr::t('indicador', 'Yes')];
            $arr_tagrupacion = TipoAgrupacion::findAll(['tagr_estado' => '1', 'tagr_estado_logico' => '1']);
            $arr_tagrupacion = ArrayHelper::map($arr_tagrupacion, "tagr_id", "tagr_nombre");
            $id = $data['id'];
            return $this->render('view', [
                'model' => Indicador::findOne($id),
                'arr_unidad_medida' => $arr_unidad_medida,
                'arr_jerarquia' => $arr_jerarquia,
                'arr_unidad' => $arr_unidad,
                'arr_tconf' => $arr_tconf,
                'arr_patron' => $arr_patron,
                'arr_obj_operativo' => $arr_obj_operativo,
                'arr_comportamiento' => $arr_comportamiento,
                'arr_tmeta' => $arr_tmeta,
                'arr_frecuencia' => $arr_frecuencia,
                'arr_fraccional' => $arr_fraccional,
                'arr_agrupacion' => $arr_agrupacion,
                'arr_tagrupacion' => $arr_tagrupacion,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionEdit() {
        $data = Yii::$app->request->get();
        if (isset($data['id'])) {
            $_SESSION['JSLANG']['Please select an Unit of Measure.'] = gpr::t('indicador', 'Please select an Unit of Measure.');
            $_SESSION['JSLANG']['Please select a Hierarchy.'] = gpr::t('indicador', 'Please select a Hierarchy.');
            $_SESSION['JSLANG']['Please select an Unity Name.'] = gpr::t('unidad', 'Please select an Unity Name.');
            $_SESSION['JSLANG']['Please select an Indicator Pattern.'] = gpr::t('indicador', 'Please select an Indicator Pattern.');
            $_SESSION['JSLANG']['Please select a Configuration Type.'] = gpr::t('tipoconfiguracion', 'Please select a Configuration Type.');
            $_SESSION['JSLANG']['Please select an Operative Objective.'] = gpr::t('objetivooperativo', 'Please select an Operative Objective.');
            $_SESSION['JSLANG']['Please select an Indicator Behavior.'] = gpr::t('indicador', 'Please select an Indicator Behavior.');
            $_SESSION['JSLANG']['Please select a Goal Type.'] = gpr::t('meta', 'Please select a Goal Type.');
            $_SESSION['JSLANG']['Please select an Indicator Frecuency.'] = gpr::t('indicador', 'Please select an Indicator Frecuency.');
            $_SESSION['JSLANG']['Please if Unit of Measure is percentage you have to select an Indicator Fractional as Yes.'] = gpr::t('indicador', 'Please if Unit of Measure is percentage you have to select an Indicator Fractional as Yes.');
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $arr_unidad_medida = UnidadMedida::findAll(['umed_estado' => '1', 'umed_estado_logico' => '1']);
            $arr_unidad_medida = ['0' => gpr::t('indicador', "-- Select an Unit of Measure --")] + ArrayHelper::map($arr_unidad_medida, "umed_id", "umed_nombre");
            $arr_jerarquia = JerarquiaIndicador::findAll(['jind_estado' => '1', 'jind_estado_logico' => '1']);
            $arr_jerarquia = ['0' => gpr::t('indicador', "-- Select a Hierarchy --")] + ArrayHelper::map($arr_jerarquia, "jind_id", "jind_nombre");
            $arr_unidad = UnidadGpr::getArrayUnidad();
            $arr_unidad = ['0' => gpr::t('unidad', '-- Select an Unity Name --')] + ArrayHelper::map($arr_unidad, "id", "name");
            $arr_tconf = TipoConfiguracion::findAll(['tcon_estado' => '1', 'tcon_estado_logico' => '1']);
            $arr_tconf = ['0' => gpr::t('tipoconfiguracion', '-- Select a Configuration Type --')] + ArrayHelper::map($arr_tconf, "tcon_id", "tcon_nombre");
            $arr_patron = PatronIndicador::findAll(['pind_estado' => '1', 'pind_estado_logico' => '1']);
            $arr_patron = ['0' => gpr::t('indicador', '-- Select an Indicator Pattern --')] + ArrayHelper::map($arr_patron, "pind_id", "pind_nombre");
            $arr_obj_operativo = ObjetivoOperativo::getArrayObjOperativo();
            $arr_obj_operativo = ['0' => gpr::t('objetivooperativo', '-- Select an Operative Objective --')] + ArrayHelper::map($arr_obj_operativo, "id", "name");
            $arr_comportamiento = ComportamientoIndicador::findAll(['cind_estado' => '1', 'cind_estado_logico' => '1']);
            $arr_comportamiento = /*['0' => gpr::t('indicador', '-- Select an Indicator Behavior --')] + */ ArrayHelper::map($arr_comportamiento, "cind_id", "cind_nombre");
            $arr_tmeta = TipoMeta::findAll(['tmet_estado' => '1', 'tmet_estado_logico' => '1']);
            $arr_tmeta = /*['0' => gpr::t('meta', '-- Select a Goal Type --')] +*/ ArrayHelper::map($arr_tmeta, "tmet_id", "tmet_nombre");
            $arr_frecuencia = FrecuenciaIndicador::findAll(['find_estado' => '1', 'find_estado_logico' => '1']);
            $arr_frecuencia = ['0' => gpr::t('indicador', '-- Select an Indicator Frecuency --')] + ArrayHelper::map($arr_frecuencia, "find_id", "find_nombre");
            $arr_fraccional =  ['0' => gpr::t('indicador', 'No'), '1' => gpr::t('indicador', 'Yes')];
            $arr_agrupacion =  ['0' => gpr::t('indicador', 'No'), '1' => gpr::t('indicador', 'Yes')];
            $arr_tagrupacion = TipoAgrupacion::findAll(['tagr_estado' => '1', 'tagr_estado_logico' => '1']);
            $arr_tagrupacion = ArrayHelper::map($arr_tagrupacion, "tagr_id", "tagr_nombre");
            $id = $data['id'];
            
            return $this->render('edit', [
                'model' => Indicador::findOne($id),
                'arr_unidad_medida' => $arr_unidad_medida,
                'arr_jerarquia' => $arr_jerarquia,
                'arr_unidad' => $arr_unidad,
                'arr_tconf' => $arr_tconf,
                'arr_patron' => $arr_patron,
                'arr_obj_operativo' => $arr_obj_operativo,
                'arr_comportamiento' => $arr_comportamiento,
                'arr_tmeta' => $arr_tmeta,
                'arr_frecuencia' => $arr_frecuencia,
                'arr_fraccional' => $arr_fraccional,
                'arr_agrupacion' => $arr_agrupacion,
                'arr_tagrupacion' => $arr_tagrupacion,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionSave() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            try {
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $empresa = (($user_id == 1)?$data["empresa"]:$idEmpresa);
                $oope_id = $data["operativo"];
                $tcon_id = $data["tconfig"];
                $pind_id = $data["patron"];
                $umed_id = $data["medida"];
                $ugpr_id = $data["unidad"];
                $jind_id = $data["jerarquia"];
                $cind_id = $data["comportamiento"];
                $tmet_id = $data["tmeta"];
                $find_id = $data["frecuencia"];
                $tagr_id = ($data["agrupacion"] == 1)?$data["tagrupacion"]:NULL;
                $linea_base = $data["baseline"];
                $meta = '0';
                $fuente = $data["fuente"];
                $calculo = $data["calculo"];
                $fraccional = $data["fraccional"];
                $agrupamiento = $data["agrupacion"];
                $fini = $data["fini"];

                $model = new Indicador();
                $model->ind_nombre = $nombre;
                $model->ind_descripcion = $descripcion;
                $model->oope_id = $oope_id;
                $model->tcon_id = $tcon_id;
                $model->pind_id = $pind_id;
                $model->umed_id = $umed_id;
                $model->ugpr_id = $ugpr_id;
                $model->jind_id = $jind_id;
                $model->cind_id = $cind_id;
                $model->tmet_id = $tmet_id;
                $model->find_id = $find_id;
                $model->tagr_id = $tagr_id;
                $model->ind_linea_base = $linea_base;
                $model->ind_meta = $meta;
                $model->ind_fuente_informe = $fuente;
                $model->ind_metodo_calculo = $calculo;
                $model->ind_fraccional = $fraccional;
                $model->ind_agrupamiento = $agrupamiento;
                $model->ind_fecha_inicio = $fini;
                $model->ind_estado = '1';
                $model->ind_usuario_ingreso = $user_id;
                $model->ind_estado_logico = "1";
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no creado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $data = Yii::$app->request->post();
            try {
                $id = $data["id"];
                $nombre = $data["nombre"];
                $descripcion = $data["desc"];
                $empresa = (($user_id == 1)?$data["empresa"]:$idEmpresa);
                $oope_id = $data["operativo"];
                $tcon_id = $data["tconfig"];
                $pind_id = $data["patron"];
                $umed_id = $data["medida"];
                $ugpr_id = $data["unidad"];
                $jind_id = $data["jerarquia"];
                $cind_id = $data["comportamiento"];
                $tmet_id = $data["tmeta"];
                $find_id = $data["frecuencia"];
                $tagr_id = ($data["agrupacion"] == 1)?$data["tagrupacion"]:NULL;
                $linea_base = $data["baseline"];
                $meta = '';
                $fuente = $data["fuente"];
                $calculo = $data["calculo"];
                $fraccional = $data["fraccional"];
                $agrupamiento = $data["agrupacion"];
                $fini = $data["fini"];

                $model = Indicador::findOne($id);
                $model->ind_nombre = $nombre;
                $model->ind_descripcion = $descripcion;
                $model->oope_id = $oope_id;
                $model->tcon_id = $tcon_id;
                $model->pind_id = $pind_id;
                $model->umed_id = $umed_id;
                $model->ugpr_id = $ugpr_id;
                $model->jind_id = $jind_id;
                $model->cind_id = $cind_id;
                $model->tmet_id = $tmet_id;
                $model->find_id = $find_id;
                $model->tagr_id = $tagr_id;
                $model->ind_linea_base = $linea_base;
                //$model->ind_meta = $meta;
                $model->ind_fuente_informe = $fuente;
                $model->ind_metodo_calculo = $calculo;
                $model->ind_fraccional = $fraccional;
                $model->ind_agrupamiento = $agrupamiento;
                $model->ind_fecha_inicio = $fini;
                $model->ind_estado = '1';
                $model->ind_usuario_ingreso = $user_id;
                $model->ind_estado_logico = "1";
                $model->ind_fecha_modificacion = $fecha_modificacion;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->update() !== false) {
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no actualizado.');
                }
            } catch (Exception $ex) {
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.'),
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }

    public function actionDelete() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $user_id = Yii::$app->session->get('PB_iduser', FALSE);
            $fecha_modificacion = date(Yii::$app->params["dateTimeByDefault"]);
            $transaction = Yii::$app->db_gpr->beginTransaction();
            $error = false;
            try {
                $id = $data["id"];
                $model = Indicador::findOne($id);
                $model->ind_estado_logico = '0';
                $model->ind_usuario_modifica = $user_id;
                $model->ind_fecha_modificacion = $fecha_modificacion;
                $message = array(
                    "wtmessage" => Yii::t("notificaciones", "Your information was successfully saved."),
                    "title" => Yii::t('jslang', 'Success'),
                );
                if ($model->save()) {
                    $arrMetas = MetaIndicador::findAll(['ind_id' => $id]);
                    foreach($arrMetas as $key => $val){
                        $val->mind_estado_logico = '0';
                        $val->mind_fecha_modificacion = $fecha_modificacion;
                        $val->mind_usuario_modifica = $user_id;
                        if($val->save()){
                            $arrMetaSeg = MetaSeguimiento::findAll(['mind_id' => $val->mind_id]);
                            foreach($arrMetaSeg as $key2 => $val2){
                                $val2->mseg_estado_logico = '0';
                                $val2->mseg_fecha_modificacion = $fecha_modificacion;
                                $val2->mseg_usuario_modifica = $user_id;
                                if($val2->save()){
                                    if(MetaAnexo::disableOldDocuments($val2->mseg_id) == false)
                                        throw new Exception('Error Imposible Desactivar documentos antiguos.');
                                }else   throw new Exception('Error Registro no ha sido eliminado.');
                            }
                        }
                        else    throw new Exception('Error Registro no ha sido eliminado.');
                    }
                    $transaction->commit();
                    return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
                } else {
                    throw new Exception('Error Registro no ha sido eliminado.');
                }
            } catch (Exception $ex) {
                $transaction->rollback();
                if($error)  $msg = $ex->getMessage();
                $message = array(
                    "wtmessage" => Yii::t('notificaciones', 'Your information has not been saved. Please try again.') . " " . $msg,
                    "title" => Yii::t('jslang', 'Error'),
                );
                return Utilities::ajaxResponse('NOOK', 'alert', Yii::t('jslang', 'Error'), 'true', $message);
            }
        }
    }
}