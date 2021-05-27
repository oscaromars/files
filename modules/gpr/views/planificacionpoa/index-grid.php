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
                'attribute' => 'Nombre',
                'header' => gpr::t("planificacionpoa", 'Poa Planning Name'),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Descripcion',
                'header' => gpr::t("planificacionpoa", 'Poa Planning Description'),
                'value' => 'Descripcion',
            ],
            [
                'attribute' => 'PlanificacionPedi',
                'header' => gpr::t("planificacionpedi", 'Pedi Planning'),
                'value' => 'PlanificacionPedi',
            ],
            [
                'attribute' => 'FechaInicio',
                'header' => gpr::t("planificacionpoa", 'Poa Planning Initial Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaInicio']));
                }
            ],
            [
                'attribute' => 'FechaFin',
                'header' => gpr::t("planificacionpoa", 'Poa Planning End Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaFin']));
                }
            ],
            [
                'attribute' => 'FechaActualizacion',
                'header' => gpr::t("planificacionpoa", 'Poa Planning Last Update Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaActualizacion']));
                }
            ],
            [
                'attribute' => 'Cierre',
                'header' => gpr::t("planificacionpoa", 'Poa Planning Closed'),
                'value' => function($data){
                    return ($data['Cierre'] == 0)?(gpr::t('planificacionpoa', 'Planning Opened')):(gpr::t('planificacionpedi', 'Planning Closed'));
                },
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("planificacionpoa", "Poa Planning Status"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.gpr::t("planificacionpoa", "Poa Planning Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.gpr::t("planificacionpoa", "Poa Planning Disabled").'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/planificacionpoa/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
