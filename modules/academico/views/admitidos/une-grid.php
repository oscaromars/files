<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\modules\academico\Module as academico;
use app\modules\admision\Module as admision;

admision::registerTranslations();
academico::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>
    <?=
    PbGridView::widget([
        'id' => 'TbG_UNE',
        //'showExport' => true,
        //'fnExportEXCEL' => "exportuneExcel",
        //'fnExportPDF' => "exportunePdf",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [                   
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'per_dni',
            ],
            [
                'attribute' => 'Nombres',
                'header' => Yii::t("formulario", "First Names"),
                'value' => 'per_nombres',
            ],
            [
                'attribute' => 'Apellidos',
                'header' => Yii::t("formulario", "Last Names"),
                'value' => 'per_apellidos',
            ],  
            [
                'attribute' => 'fecha',
                'header' => Yii::t("formulario", "Date"),
                'value' => 'fecha',
            ],
            [
                'attribute' => 'estado',
                'header' => Yii::t("formulario", "Status"),
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'value' => function ($model) {
                    if ($model["odoc_estado_aprobacion"] == 1)
                        return '<small class="label label-warning">Pendiente</small>';                    
                    if ($model["odoc_estado_aprobacion"] == 2)                        
                        return '<small class="label label-success">Aprobado</small>';
                    if ($model["odoc_estado_aprobacion"] == 3)                        
                        return '<small class="label label-danger">No Aprobado</small>';
                },                
            ],  
            /*[
                'class' => 'yii\grid\ActionColumn',
                'header' => academico::t("Academico", "Career/Program"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['carrera'], 0,10)  . '..</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['carrera']]);
                    },
                ],               
            ],*/           
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{documento}', //
                'buttons' => [      
                    'documento' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-check"></span>', Url::to(['/academico/matriculacion/documento', 'per_id' => base64_encode($model['per_id'])]), ["data-toggle" => "tooltip", "title" => "Revisar Documento", "data-pjax" => 0]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>