<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => gpr::t("objetivoestrategico", "Strategic Objective Name"),
                'template' => '{text}',
                'buttons' => [
                    'text' => function ($url, $model) {
                        if(strlen($model['Nombre']) < 70)
                            return $model['Nombre'];
                        return Html::a('<span>' . substr($model['Nombre'], 0, 70) . '...</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['Nombre']]);
                    },
                ],
            ],
            /*[
                'attribute' => 'Descripcion',
                'header' => gpr::t("objetivoestrategico", 'Strategic Objective Description'),
                'value' => 'Descripcion',
            ],*/
            [
                'attribute' => 'PlanificacionPedi',
                'header' => gpr::t("planificacionpedi", 'Pedi Planning'),
                'value' => 'PlanificacionPedi',
            ],
            [
                'attribute' => 'Enfoque',
                'header' => gpr::t("enfoque", 'Focus'),
                'value' => 'Enfoque',
            ],
            [
                'attribute' => 'CategoriaBSC',
                'header' => gpr::t("categoriabsc", 'Category BSC'),
                'value' => 'CategoriaBSC',
            ],
            [
                'attribute' => 'FechaInicio',
                'header' => gpr::t("objetivoestrategico", 'Strategic Objective Initial Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaInicio']));
                }
            ],
            [
                'attribute' => 'FechaFin',
                'header' => gpr::t("objetivoestrategico", 'Strategic Objective End Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaFin']));
                }
            ],
            [
                'attribute' => 'FechaActualizacion',
                'header' => gpr::t("objetivoestrategico", 'Strategic Objective Last Update Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaActualizacion']));
                }
            ],
            [
                'attribute' => 'CierrePedi',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("planificacionpedi", "Pedi Planning Closed"),
                'value' => function($data){
                    if($data["CierrePedi"] == "0")
                        return '<small class="label label-success">'.gpr::t("planificacionpedi", "Planning Opened").'</small>';
                    else
                        return '<small class="label label-danger">'.gpr::t("planificacionpedi", "Planning Closed").'</small>';
                },
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("objetivoestrategico", "Strategic Objective Status"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.gpr::t("objetivoestrategico", "Strategic Objective Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.gpr::t("objetivoestrategico", "Strategic Objective Disabled").'</small>';
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '60'],
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/objetivoestrategico/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
