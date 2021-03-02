<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\gpr\Module as gpr;
gpr::registerTranslations();


$template = '{view} {delete} {resultado}';
$buttons = [
    'view' => function ($url, $model) {
        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/hito/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
    },
    'delete' => function ($url, $model) {
        return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
    },
    'resultado' => function ($url, $model) {
        return Html::a('<span class="fa fa-registered"></span>', Url::to(['/gpr/hitoresultado/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => gpr::t("hito","View Milestone Result")]);
    },
];
if($isAdmin){
    $template .= ' {open}';
    $buttons['open'] = function ($url, $model) {
        return Html::a('<span class="fa fa-unlock-alt"></span>', null, ['href' => 'javascript:openHito('.$model['id'].');', "data-toggle" => "tooltip", "title" => gpr::t("hito","Open Milestone")]);
    };
}

?>

<?=
    PbGridView::widget([
        'id' => 'grid_list_hito',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => gpr::t("hito", "Milestone Name"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'FechaCompromiso',
                'header' => gpr::t("hito", 'Deliver Date'),
                'value' => function($data){
                    return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaCompromiso']));
                },
            ],
            [
                'attribute' => 'FechaReal',
                'header' => gpr::t("hito", 'Actual Date'),
                'value' => function($data){
                    if(isset($data['FechaReal']) && $data['FechaReal'] != "")
                        return date(Yii::$app->params["dateByDefault"], strtotime($data['FechaReal']));
                    return "-";
                },
            ],
            [
                'attribute' => 'HitoCumplido',
                'header' => gpr::t("hito", 'Milestone Accomplished'),
                'value' => function($data){
                    if($data['HitoCumplido'] == '1'){
                        return gpr::t("hito", "Yes");
                    }
                    return gpr::t("hito", "No");
                },
            ],
            [
                'attribute' => 'Peso',
                'header' => gpr::t("hito", 'Weighing'),
                'value' => function($data){
                    return $data['Peso']."%";
                },
            ],
            [
                'attribute' => 'Avance',
                'header' => gpr::t("hito", 'Progress'),
                'value' => function($data){
                    return $data['Avance']."%";
                },
            ],
            [
                'attribute' => 'Presupuesto',
                'header' => gpr::t("hito", 'Budget'),
                'value' => function($data){
                    return "$".(number_format($data['Presupuesto'], 2, '.', ','));
                },
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("hito", "Milestone Status"),
                'value' => function($data){
                    $cumplido = $data['HitoCumplido'];
                    $fechaEstimada = $data['FechaCompromiso'];
                    $fechaReal = $data['FechaReal'];
                    $proyectoIni = $data['ProyectoInicio'];
                    $current = date(Yii::$app->params["dateTimeByDefault"]);
                    $diffDias = Utilities::getDiffDate($proyectoIni, $fechaEstimada);
                    $promDias = $diffDias / 2;
                    $currentDias = Utilities::getDiffDate($proyectoIni, $current);
                    if($cumplido == 1){
                        $diffDiasFin = Utilities::getDiffDate($proyectoIni, $fechaReal);
                        if($diffDiasFin <= $diffDias)
                            return '<small class="label label-success">'.gpr::t("hito", "Finished").'</small>';
                        return '<small class="label label-danger">'.gpr::t("hito", "Finished").'</small>';
                    }
                    if($promDias > $currentDias)
                        return '<small class="label label-success">'.gpr::t("hito", "On Time").'</small>';
                    elseif($currentDias > $diffDias)
                        return '<small class="label label-danger">'.gpr::t("hito", "Unfinished").'</small>';
                    else
                        return '<small class="label label-warning">'.gpr::t("hito", "Delay").'</small>';
                },
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
