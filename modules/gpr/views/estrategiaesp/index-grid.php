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
                'header' => gpr::t("estrategiaesp", "Strategic Name"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Descripcion',
                'header' => gpr::t("estrategiaesp", 'Strategic Description'),
                'value' => 'Descripcion',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => gpr::t("objetivoespecifico", "Specific Objective Name"),
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
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => gpr::t("estrategiaesp", "Strategic Status"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.gpr::t("estrategiaesp", "Strategic Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.gpr::t("estrategiaesp", "Strategic Disabled").'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['/gpr/estrategiaesp/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                        //return  Html::a('Action', Url::to(['mceformulariotemp/solicitudpdf','ids' => 1],['class' => 'btn btn-default',"target" => "_blank"]));
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>
