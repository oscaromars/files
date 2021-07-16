<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Utilities;
use app\widgets\PbGridView\PbGridView;
use app\modules\admision\Module as admision;

admision::registerTranslations();
?>
<?= Html::hiddenInput('txth_ids', '', ['id' => 'txth_ids']); ?>
<!--<div>
<?=
    $this->render('academicoestudiante-grid', [
        'model' => $model,
    ]);
    ?>
</div> -->
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
