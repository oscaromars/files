<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();

$template = '{view} {delete} {hito}';
$buttons = [
    'view' => function ($url, $model) {
        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/proyecto/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
    },
    'hito' => function($url, $model) {
        return Html::a('<span class="fa fa-cubes"></span>', Url::to(['/gpr/hito/index', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => gpr::t("hito","Milestone")]);
    },
    'delete' => function ($url, $model) {
        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
    },
];
if($isAdmin){
    $template .= ' {open}';
    $buttons['open'] = function ($url, $model) {
        return Html::a('<span class="fa fa-unlock-alt"></span>', null, ['href' => 'javascript:openProject('.$model['id'].');', "data-toggle" => "tooltip", "title" => gpr::t("proyecto","Open Project")]);
    };
}
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
                'header' => gpr::t("proyecto", "Project Name"),
                'value' => 'Nombre',
            ],
            /*[
                'attribute' => 'Descripcion',
                'header' => gpr::t("proyecto", 'Project Description'),
                'value' => 'Descripcion',
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => gpr::t("objetivooperativo", 'Operative Objective'),
                'template' => '{text}',
                'buttons' => [
                    'text' => function ($url, $model) {
                        if(strlen($model['Objetivo']) < 70)
                            return $model['Objetivo'];
                        return Html::a('<span>' . substr($model['Objetivo'], 0, 70) . '...</span>', Url::to(['#']), ["data-toggle" => "tooltip", "title" => $model['Objetivo']]);
                    },
                ],
            ],
            [
                'attribute' => 'Unidad',
                'header' => gpr::t("unidad", 'Unity'),
                'value' => 'Unidad',
            ],
            [
                'attribute' => 'TipoProyecto',
                'header' => gpr::t("tipoproyecto", 'Project Type'),
                'value' => 'TipoProyecto',
            ],
            [
                'attribute' => 'Responsable',
                'header' => gpr::t("responsablesubunidad", 'Responsible'),
                'value' => 'Responsable',
            ],
            [
                'attribute' => 'FechaInicio',
                'header' => gpr::t("proyecto", 'Initial Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"] ,strtotime($data['FechaInicio']));
                },
            ],
            [
                'attribute' => 'FechaFin',
                'header' => gpr::t("proyecto", 'End Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"] ,strtotime($data['FechaFin']));
                },
            ],
            [
                'attribute' => 'Presupuesto',
                'header' => gpr::t("proyecto", 'Budget'),
                'value' => function($data){
                    return "$".(number_format($data['Presupuesto'], 2, '.', ','));
                },
            ],
            [
                'attribute' => 'Consumo',
                'header' => gpr::t("proyecto", 'Budget Consumed'),
                'value' => function($data){
                    return "$".(number_format($data['Consumo'], 2, '.', ','));
                },
            ],
            [
                'attribute' => 'Avance',
                'header' => gpr::t("proyecto", 'Advance'),
                'value' => function($data){
                    return $data['Avance']."%";
                },
            ],
            [
                'attribute' => 'CantHito',
                'header' => gpr::t("hito", 'Milestones'),
                'value' => 'CantHito',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                //'header' => 'Action',
                'contentOptions' => ['style' => 'text-align: center;'],
                'headerOptions' => ['width' => '90'],
                'template' => $template,
                'buttons' => $buttons,
            ],
        ],
    ])
?>
