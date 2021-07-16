<?php

use yii\helpers\Html;
use app\widgets\PbGridView\PbGridView;
$this->title = Yii::t('app', 'Resumen de Planificacion');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>

<div class="row">
   
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="form-group">
             <label for="lbl_modalidad" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Mode"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_modalidadesacad", 0, $arr_modalidad, ["class" => "form-control", "id" => "cmb_modalidadesacad"]) ?>
            </div> 
            <label for="lbl_periodo" class="col-sm-2 col-lg-2 col-md-2 col-xs-2 control-label"><?= Yii::t("formulario", "Period"); ?></label>
            <div class="col-sm-3 col-md-3 col-xs-3 col-lg-3">
                <?= Html::dropDownList("cmb_periodoacad", 0, $arr_periodo, ["class" => "form-control", "id" => "cmb_periodoacad"]) ?>
            </div>                  
        </div>        
    </div>        
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">  
        <div class="col-sm-8"></div>
        <div class="col-sm-2">                
            <a id="btn_buscarPlanestudiante" href="javascript:" class="btn btn-primary btn-block"> <?= Yii::t("formulario", "Search") ?></a>
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
            /*[
                'attribute' => 'Modalidad',
                'header' => Yii::t("formulario", "Modalidad"),
                'value' => 'id_modalidad',
            ],*/
            /*
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Accion',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="' . Utilities::getIcon('view') . '"></span>', Url::to(['planificacion/view', 'pla_id' => $model['pla_id'], 'per_id' => $model['per_id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion", "View")]);
                    },
                    'delete' => function ($url, $model) {
                    //if ($model['est_id'] > 1 && $model["estado"] == 'Activo') {
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', "#", ['onclick' => "deleteplanestudiante(" . $model['pla_id'] . ", " . $model['per_id'] . ");", "data-toggle" => "tooltip", "title" => "Eliminar Planificación", "data-pjax" => 0]);
                    //} else {
                        return '<span class="glyphicon glyphicon glyphicon-remove"></span>';
                    //}
                }
                ],
            ],*/
        ],
    ])
    ?>
</div>   
