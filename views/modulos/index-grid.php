<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
?>

<?=
    PbGridView::widget([
        'id' => 'grid_module_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Nombre',
                'header' => Yii::t("modulo", "Module Name"),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Aplicacion',
                'header' => Yii::t("aplicacion", "Application Name"),
                'value' => 'Aplicacion',
            ],
            [
                'attribute' => 'Tipo',
                'header' => Yii::t("modulo", "Type of Module"),
                'value' => 'Tipo',
            ],
            [
                'attribute' => 'Orden',
                'header' => Yii::t("modulo", "Position Module"),
                'value' => 'Orden',
            ],
            [
                'attribute' => 'Estado',
                'format' => 'html',
                'header' => Yii::t("modulo", "Status Module"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.Yii::t("modulo", "Module Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.Yii::t("modulo", "Module Disabled").'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['modulos/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
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
