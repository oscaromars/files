<?php

namespace app\modules\gpr\controllers;

use Yii;
use app\models\Utilities;
use app\modules\gpr\models\PlanificacionPedi;
use app\modules\gpr\models\PlanificacionPoa;
use app\modules\gpr\models\Proyecto;
use app\modules\gpr\models\ResponsableUnidad;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

class PresupuestoController extends \app\components\CController {
    public function actionIndex(){
        $model = new Proyecto();
        $data = Yii::$app->request->get();
        $user_id = Yii::$app->session->get('PB_iduser', FALSE);
        $idEmpresa = Yii::$app->session->get("PB_idempresa", FALSE);
        $isAdmin = ResponsableUnidad::userIsAdmin($user_id, $idEmpresa);
        if (isset($data["PBgetFilter"])) {
            $tplan = $data['tplan'];
            $id = $data['id'];
            if($tplan == 1){ // PEDI
                $presupuestoTotal = $model->getTotalPresupuestoByPedi($id);
                return $this->renderPartial('index-grid', [
                    "model" => $model->getPresupuestoByPedi($id, true),
                    "presupuestoTotal" => $presupuestoTotal,
                    'isAdmin' => $isAdmin,
                ]);
            }else{ // POA
                $presupuestoTotal = $model->getTotalPresupuestoByPoa($id);
                return $this->renderPartial('index-grid', [
                    "model" => $model->getPresupuestoByPoa($id, true),
                    'presupuestoTotal' => $presupuestoTotal,
                    'isAdmin' => $isAdmin,
                ]);
            }
            
        }
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["getPlan"])) {
                $tplan = $data['tplan'];
                $planes = array();
                if($tplan == 1){ // PEDI
                    $planes = PlanificacionPedi::getArrayPlanPedi();
                }else{ // POA
                    $planes = PlanificacionPoa::getArrayPlanPoa();
                }
                $arr_planes = array_merge(['0' => ['id' => '0', 'name' => gpr::t('proyecto', '-- Select an Option --')]], $planes);
                $message = array("planes" => $arr_planes);
                return Utilities::ajaxResponse('OK', 'alert', Yii::t('jslang', 'Success'), 'false', $message);
            }
        }
        $arr_tplan = ['0' => gpr::t('proyecto', '-- Select a type for search by --'), '1' => gpr::t('planificacionpedi', 'Pedi Planning'), '2' => gpr::t('planificacionpoa', 'Poa Planning')];
        $arr_filter = ['0' => gpr::t('proyecto', '-- Select an Option --')];
        return $this->render('index', [
            'model' => new ArrayDataProvider(array()),
            'arr_tplan' => $arr_tplan,
            'arr_filter' => $arr_filter,
            'isAdmin' => $isAdmin,
            'presupuestoTotal' => 0,
        ]);
    }
}