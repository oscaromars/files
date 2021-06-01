<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\PbGridView\PbGridView;
use app\models\Utilities;
use app\modules\financiero\Module as financiero;
financiero::registerTranslations();
?>

<?=
    PbGridView::widget([
        'id' => 'grid_unidadmedidas_list',
        'showExport' => false,
        //'fnExportEXCEL' => "exportExcel",
        //'fnExportPDF' => "exportPdf",
        'dataProvider' => $model,
        'pajax' => true,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn', 'options' => ['width' => '10']],
            [
                'attribute' => 'Codigo',
                'header' => financiero::t("unidad_medida", "Code"),
                'value' => 'Codigo',
            ],
            [
                'attribute' => 'Nombre',
                'header' => financiero::t("unidad_medida", 'Name'),
                'value' => 'Nombre',
            ],
            [
                'attribute' => 'Medida',
                'header' => financiero::t("unidad_medida", 'Measure'),
                'value' => 'Medida',
            ],
            [
                'attribute' => 'Estado',
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
                'format' => 'html',
                'header' => financiero::t("unidad_medida", "Status"),
                'value' => function($data){
                    if($data["Estado"] == "1")
                        return '<small class="label label-success">'.financiero::t("unidad_medida", "Enabled").'</small>';
                    else
                        return '<small class="label label-danger">'.financiero::t("unidad_medida", "Disabled").'</small>';
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
                        return Html::a('<span class="'.Utilities::getIcon('view').'"></span>', Url::to(['unidadmedida/view', 'id' => $model['id']]), ["data-toggle" => "tooltip", "title" => Yii::t("accion","View")]);
                    },
                    'delete' => function ($url, $model) {
                         return Html::a('<span class="'.Utilities::getIcon('remove').'"></span>', null, ['href' => 'javascript:confirmDelete(\'deleteItem\',[\'' . $model['id'] . '\']);', "data-toggle" => "tooltip", "title" => Yii::t("accion","Delete")]);
                    },
                ],
            ],
        ],
    ])
?>