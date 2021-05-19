<?php

namespace app\controllers;
use Yii;
use app\components\CController;
use app\models\Grupo;
use app\models\Rol;
use app\models\Accion;
use app\models\Modulo;
use app\models\ObjetoModulo;
use app\models\Aplicacion;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use yii\base\Exception;
use app\models\Utilities;
use app\models\ReporteMatriculados;
use app\models\ExportFile;
use app\modules\academico\models\PeriodoAcademico;
use app\modules\academico\Module as academico;
use app\modules\financiero\Module as financiero;
academico::registerTranslations();
financiero::registerTranslations();

class ReportesmatriculadosController extends CController {

	public function actionIndex() {    
        $mod_periodo = new PeriodoAcademico();
        $arr_periodo = $mod_periodo->getPeriodoAcademicoActual();
        return $this->render('index',[
            'arr_periodo' => ArrayHelper::map($arr_periodo, "id", "name"),
            'model' => $model,
            //'arr_periodo' => ArrayHelper::map(array_merge([["id" => "0", "value" => "Todas"]], $arr_periodo), "id", "value"),
            
        ]);
    }

}