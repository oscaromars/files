<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<div>        
    <?=
    PbGridView::widget([
        'id' => 'PbPlanificaestudiante',
        'showExport' => true,
        'fnExportEXCEL' => "exportExcelplanifica",
        'fnExportPDF' => "exportPdfplanifica",
        'dataProvider' => $model,
        'columns' => [
            [
                'attribute' => 'DNI',
                'header' => Yii::t("formulario", "DNI 1"),
                'value' => 'per_cedula',
            ],
            [
                'attribute' => 'Estudiante',
                'header' => Yii::t("formulario", "Student"),
                'value' => 'pes_nombres',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t("crm", "Carrera"),
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        if (strlen($model['pes_carrera']) > 30) {
                            $texto = '...';
                        }
                        return Html::a('<span>' . substr($model['pes_carrera'], 0, 30) . $texto . '</span>', "javascript:", ["data-toggle" => "tooltip", "title" => $model['pes_carrera']]);
                    },
                ],
            ],
            [
                'attribute' => 'periodo',
                'header' => Yii::t("formulario", "Period"),
                'value' => 'pla_periodo_academico',
            ],
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
                    /*} else {
                        return '<span class="glyphicon glyphicon glyphicon-remove"></span>';
                    }*/
                }
                ],
            ],
        ],
    ])
    ?>
</div>   
