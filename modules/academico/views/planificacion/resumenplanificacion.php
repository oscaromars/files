<?php

use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\academico\Module as academico;
use kartik\select2\Select2;
academico::registerTranslations();
$this->title = Yii::t('app', 'Resumen de Planificacion');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
             <label for="lbl_modalidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidades", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidades"]) ?>
            </div> 
            <label for="lbl_periodo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodo", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodo"]) ?>
            </div>                  
        </div>        
    </div>        
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarResumenestudiante" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Busqueda") ?></a>
        </div>
    </div>
</div></br>

<div>        
    <?=
    PbGridView::widget([
        'id' => 'PbPlanificaestudiante',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelplanificacion",
        'fnExportPDF' => "exportPdfplanificacion",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'Materia',
                'header' => Yii::t("formulario", "Materia"),
                'value' => 'Materia',
            ],
            [
                'attribute' => 'Cantidad',
                'header' => Yii::t("formulario", "Cantidad"),
                'value' => 'Cantidad',
            ],
        ],
    ])
    ?>
</div>   

