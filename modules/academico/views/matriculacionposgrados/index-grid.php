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
        'id' => 'TbG_PROGRAMA',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcel",
        'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        //'pajax' => false,
        'columns' =>
        [
            [
                'attribute' => 'codigo',
                'header' => Yii::t("formulario", "Code"),
                'value' => 'codigo',
            ],
            [
                'attribute' => 'anio',
                'header' => Yii::t("formulario", "Year"),
                'value' => 'anio',
            ],
            [
                'attribute' => 'mes',
                'header' => Yii::t("formulario", "Month"),
                'value' => 'mes',
            ],
            [
                'attribute' => 'unidad_academica',
                'header' => academico::t("Academico", "Aca. Uni."),
                'value' => 'unidad',
            ],
            [
                'attribute' => 'modalidad',
                'header' => academico::t("Academico", "Modality"),
                'value' => 'modalidad',
            ],
            [
                //'class' => 'yii\grid\ActionColumn',
                'attribute' => 'programa',
                'header' => Yii::t("formulario", "Program"),
                'value' => 'programa',
                /*'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span>' . substr($model['programa'], 0, 35) . '...</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['programa']]);
                    },
                ],*/
            ],
            [
                'attribute' => 'paralelo',
                'header' => academico::t("Academico", "Parallels"),
                'value' => 'paralelo',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("formulario", "Actions"),
                'template' => '{view} {paralelo}', //        
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list-alt"></span>', Url::to(['/academico/matriculacionposgrados/viewpromocion', 'ids' => base64_encode($model['id'])]), ["data-toggle" => "tooltip", "title" => "Ver Promoción", "data-pjax" => 0]);
                    },
                    'paralelo' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-list"></span>', Url::to(['/academico/matriculacionposgrados/indexparalelo', 'ids' => base64_encode($model['id'])]), ["data-toggle" => "tooltip", "title" => "Ver Paralelos", "data-pjax" => 0]);
                    },
                ],
            ],
        ],
    ])
    ?>
</div>